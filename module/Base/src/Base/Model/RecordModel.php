<?php

/**
 * Created by minhbn
 * Date: 08/20/2013
 * Category: Restaurant
 */
namespace Base\Model;

use Zend\Db\Sql\Expression;

class RecordModel extends Base {

	// Các trường luôn phải khai báo
	protected $attributes;
	protected $_attributes = array ();
	protected $_new = true;
	protected $_primaryname;
	protected $_errors = array ();
	//
	public function AttributeDefault() {
		return array ();
	}
	// Set attribute default
	public function setAttributeDefault() {
		$default = $this->attributeDefault ();
		if ($default && is_array ( $default )) {
			foreach ( $default as $name => $value )
				$this->setAttribute ( $name, $value );
		}
		// else {
		// $class_vars = get_class_vars ( get_class ( $this ) );
		// foreach ( $class_vars as $name => $value ) {
		// $this->setAttribute ( $name, $value );
		// }
		// }
	}
	//
	public function attributeLabels() {
		return array (
			//'attribute'=>'label',
		);
	}
	// Lấy tên của attribute
	public function getAttributeLabel($attribute = '') {
		if ($attribute) {
			$attributelabels = $this->attributeLabels ();
			if ($attributelabels [$attribute])
				return $attributelabels [$attribute];
		}
		return $attribute;
	}
	//
	public function __construct() {
		$tablename = $this->tableName ();
		if (! $tablename)
			throw new \Exception ( 'Mỗi một RecordModel phải gắn với một bảng. Hãy khai báo tên bảng bằng cách ghi đè hàm tableName() và trả về tên bảng trong hàm đó.' );
		if (! $this->getPrimaryName ())
			throw new \Exception ( 'Mỗi một RecordModel phải có một khóa chính. Hãy khai báo khóa chính bằng cách ghi đè hàm getPrimaryName() và trả về tên khóa chính trong hàm đó.' );
		$this->setAttributeDefault ();
	}
	// PHP getter magic method.
	public function __get($name) {
		if ($name == 'attributes') {
			return $this->getAttributes ();
		} else {
			if (isset ( $this->_attributes [$name] ))
				return $this->_attributes [$name];
			elseif (property_exists ( $this, $name ))
				return $this->$name;
			else
				return parent::__get ( $name );
		}
	}
	// PHP setter magic method.
	public function __set($name, $value) {
		$this->setAttribute ( $name, $value );
	}
	//
	public function init() {
	}
	//
	public function setAttribute($name, $value) {
		if (property_exists ( $this, $name )) {
			$this->$name = $value;
			$this->_attributes [$name] = $value;
		} else {
			return false;
		}
		return true;
	}
	//
	public function setAttributes($attributes = null) {
		if (! $attributes)
			return false;
		if (! is_array ( $attributes ))
			return false;
		foreach ( $attributes as $name => $value )
			$this->setAttribute ( $name, $value );
	}
	// get attribute
	public function getAttributes() {
		return $this->_attributes;
	}
	// Tên của bảng
	public function tableName() {
		// return 'room_floors';
	}
	//
	public function getPrimaryName() {
		if ($this->_primaryname)
			return $this->_primaryname;
	}
	public function setPrimaryName($name = '') {
		$name .= '';
		$name = trim ( $name );
		if (! $name)
			return false;
		$this->_primaryname = $name;
	}
	public function getPrimaryKey() {
		$attributes = $this->getAttributes ();
		return $attributes [$this->getPrimaryName ()];
	}
	//
	public function isNewRecord() {
		return $this->_new;
	}
	public function setNewRecord($value) {
		$this->_new = $value;
	}
	// validate dữ liệu
	public function validate($attributes = null) {
		return true;
	}
	// beforeSave
	public function beforeSave() {
		return true;
	}
	// afterSave
	public function afterSave() {
		return true;
	}
	//
	public function save() {
		if ($this->validate ()) {
			return $this->isNewRecord () ? $this->insert () : $this->update ();
		} else
			return false;
	}
	// Insert dữ liệu vào db
	public function insert() {
		if (! $this->isNewRecord ()) {
			throw new \Exception ( 'Không thể insert dữ liệu khi record đã tồn tại' );
		}
		if ($this->beforeSave ()) {
			$attributes = $this->getAttributes ();
			$sql = $this->getDbSql ();
			$insert = $sql->insert ( $this->tableName () );
			$insert->columns ( array_keys ( $attributes ) );
			$insert->values ( $attributes );
			$insertString = $sql->getSqlStringForSqlObject ( $insert );
			try {
				$result = $this->query ( $insertString );
			} catch ( \Exception $e ) {
				throw new \Exception ( 'Có lỗi xảy ra khi insert dữ liệu\n<br>' . $insertString . '<br>' . $e->getMessage () );
			}
			if ($result) {
				$lastinsert = ( int ) $result->getGeneratedValue ();
				$this->setAttribute ( $this->getPrimaryName (), $lastinsert );
				$this->setNewRecord ( false );
				$this->afterSave ();
				return true;
			}
		}
		return false;
	}
	// Cập nhập dữ liệu
	public function update() {
		if ($this->isNewRecord ()) {
			throw new \Exception ( 'Không thể update dữ liệu khi record không tồn tại' );
		}
		//
		if ($this->beforeSave ()) {
			$attributes = $this->getAttributes ();
			unset ( $attributes [$this->getPrimaryName ()] );
			$sql = $this->getDbSql ();
			//
			$update = $sql->update ( $this->tableName () );
			$update->set ( $attributes );
			$update->where ( array (
					$this->getPrimaryName () => $this->getPrimaryKey ()
			) );
			$updateString = $sql->getSqlStringForSqlObject ( $update );
			$resource = $this->query ( $updateString );
			if ($resource) {
				$this->afterSave ();
				return true;
			}
		}
		return false;
	}
	//
	public function beforeFind() {
		return true;
	}
	// Trả về một mảng dữ liệu hay một khối dữ liệu dc limit
	public function find($condition = '', $returnobject = false, $offset = 0, $limit = 0) {
		if (! $condition)
			return false;
		$sql = $this->getDbSql ();
		$select = $sql->select ( $this->tableName () );
		$select->where ( array (
				$condition
		) );
		if ($limit) {
			$select->offset ( $offset );
			$select->limit ( $limit );
		}
		// $sqlstring = 'SELECT * FROM ' . $this->tableName () . ' WHERE ' . $condition;
		$sqlstring = $sql->getSqlStringForSqlObject ( $select );
		$resource = $this->query ( $sqlstring );
		if ($resource) {
			if (! $returnobject)
				return $resource->toArray ();
			else {
				$data = $resource->toArray ();
				$return = array ();
				foreach ( $data as $row ) {
					$object = $this->getNewObject ();
					$object->setAttributes ( $row );
					$return [] = $object;
				}
				return $return;
			}
		}
		return false;
	}
	// Trả về một mảng dữ liệu hay trả về một đối tượng
	public function findAll($condition = '', $returnobject = false) {
		if (! $condition)
			return false;
		$sqlstring = 'SELECT * FROM ' . $this->tableName () . ' WHERE ' . $condition;
		$resource = $this->query ( $sqlstring );
		if ($resource) {
			if (! $returnobject)
				return $resource->toArray ();
			else {
				$data = $resource->toArray ();
				$return = array ();
				foreach ( $data as $row ) {
					$object = $this->getNewObject ();
					$object->setAttributes ( $row );
					$return [] = $object;
				}
				return $return;
			}
		}
		return false;
	}
	// Tìm bản ghi theo id
	public function findByPk($id, $condition = '') {
		$this->beforeFind ();
		if (! $id)
			return false;
		$tablename = $this->tableName ();
		if (! $tablename)
			return false;
		$sql = $this->getDbSql ();
		$select = $sql->select ();
		$select->from ( $tablename );
		$select->where ( array (
				$this->getPrimaryName () => $id
		) );
		$selectString = $sql->getSqlStringForSqlObject ( $select );
		$resource = $this->query ( $selectString );
		if (! $resource)
			return false;
		$result = $resource->current ();
		//
		if ($result) {
			$model = $this->getNewObject ();
			// $model = new static();
			// $model->setServiceLocator ( $this->getServiceLocator () );
			$model->setNewRecord ( false );
			foreach ( $result as $name => $value ) {
				$model->setAttribute ( $name, $value );
			}
			//
			return $model;
		}
		return false;
	}
	// tìm theo các thuộc tính có trong bảng
	public function findByAttributes($attributes = null, $returnobject = false, $offset = 0, $limit = 0) {
		$this->beforeFind ();
		if (! $attributes)
			return false;
		$sql = $this->getDbSql ();
		$select = $sql->select ( $this->tableName () );
		$select->where ( $attributes );
		if ($limit) {
			$select->limit ( $limit );
			$select->offset ( $offset );
		}
		$selectString = $sql->getSqlStringForSqlObject ( $select );
		$resource = $this->query ( $selectString );
		if ($resource) {
			if (! $returnobject)
				return $resource->toArray ();
			else {
				$data = $resource->toArray ();
				$return = array ();
				foreach ( $data as $row ) {
					$object = $this->getNewObject ();
					$object->setAttributes ( $row );
					$return [] = $object;
				}
				return $return;
			}
		}
		return false;
	}
	// Tìm theo một query
	public function findBySql($sql = '', $returnobject = false) {
		$this->beforeFind ();
		if (! $sql)
			return false;
		$resource = $this->query ( $sql );
		if ($resource) {
			if (! $returnobject)
				return $resource->toArray ();
			else {
				$data = $resource->toArray ();
				$return = array ();
				foreach ( $data as $row ) {
					$object = $this->getNewObject ();
					$object->setAttributes ( $row );
					$return [] = $object;
				}
				return $return;
			}
		}
		return false;
	}
	//
	// Xóa
	public function beforeDelete() {
		return true;
	}
	//
	public function delete() {
		if (! $this->isNewRecord ()) {
			if ($this->beforeDelete ()) {
				return $this->deleteByPk ( $this->getPrimaryKey () );
			}
			return false;
		} else
			throw new \Exception ( 'Không thể xóa bởi vì record không tồn tại' );
	}
	// Xóa record theo id
	public function deleteByPk($id = null) {
		if (! $id)
			return false;
		$sql = $this->getDbSql ();
		$delete = $sql->delete ( $this->tableName () );
		$delete->where ( array (
				$this->getPrimaryName () => $id
		) );
		$deleteString = $sql->getSqlStringForSqlObject ( $delete );
		$resource = $this->query ( $deleteString );
		if ($resource)
			return true;
		return false;
	}
	// Xóa theo các thuộc tính
	public function deleteByAttributes($attributes = null) {
		if (! $attributes)
			return false;
		$sql = $this->getDbSql ();
		$delete = $sql->delete ( $this->tableName () );
		$delete->where ( $attributes );
		$deleteString = $sql->getSqlStringForSqlObject ( $delete );
		$resource = $this->query ( $deleteString );
		if ($resource)
			return true;
		return false;
	}
	// Đếm theo một query
	public function beforeCount() {
		return true;
	}
	// Đếm tất cả các record trong table name
	public function countAll() {
		$this->beforeCount ();
		$sql = $this->getDbSql ();
		$select = $sql->select ( $this->tableName () );
		$select->columns ( array (
				'num' => new \Zend\Db\Sql\Expression ( 'COUNT(*)' )
		) );
		$queryString = $sql->getSqlStringForSqlObject ( $select );
		return $this->countBySql ( $queryString );
	}
	//
	public function countByAttributes($attributes = null) {
		$this->beforeCount ();
		if (! $attributes)
			return 0;
		$sql = $this->getDbSql ();
		$select = $sql->select ( $this->tableName () );
		$select->columns ( array (
				'num' => new \Zend\Db\Sql\Expression ( 'COUNT(*)' )
		) );
		$select->where ( $attributes );
		$queryString = $sql->getSqlStringForSqlObject ( $select );
		return $this->countBySql ( $queryString );
	}
	//
	public function countBySql($sql = '') {
		$this->beforeCount ();
		if (! $sql)
			return 0;
		$resource = $this->query ( $sql );
		$result = $resource->current ();
		if ($result) {
			foreach ( $result as $count )
				return ( int ) $count;
		}
		return 0;
	}
	//
	public function addError($attribute = '', $message = '') {
		if (property_exists ( $this, $attribute )) {
			$this->_errors [$attribute] [] = $message;
		}
	}
	// Lấy tất cả lỗi
	public function getErrors() {
		return $this->_errors;
	}
	// Kiểm tra xem có lỗi hay không
	public function hasErrors() {
		if (count ( $this->_errors ))
			return true;
		return false;
	}
	// trả về một đối tượng mới
	public function getNewObject() {
		$object = new $this ();
		$object->setServiceLocator ( $this->getServiceLocator () );
		return $object;
	}
	// Trả về một resource
	public function query($sql = '') {
		if (! $sql)
			return false;
		$adapter = $this->getServiceLocator ()->get ( 'dbAdapter' );
		$resource = $adapter->query ( $sql, $adapter::QUERY_MODE_EXECUTE );
		return $resource;
	}
	public function getDbSql() {
		return $this->getServiceLocator ()->get ( 'dbSql' );
	}
	//
	//
}