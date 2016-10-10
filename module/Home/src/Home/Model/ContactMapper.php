<?php
namespace Home\Model;

use Base\Mapper\Base;
use Home\Model\Contact;

class ContactMapper extends Base{

    CONST TABLE_NAME = 'contact';


    public function get($ob)
    {
        if(!$ob->getId()){
            return null;
        }
        $select = $this->getDbSql()->select(array('c' => self::TABLE_NAME ));
        if ($ob->getId()) {
            $select->where(['c.id' => $ob->getId()]);
        }
        if($ob->getOption('limit')){
            $select->limit($ob->getOption('limit'));
        }else{
            $select->limit(1);
        }
        $dbAdapter = $this->getDbAdapter();
        $query = $this->getDbSql()->buildSqlString($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if ($results->count()) {
            $contacts = array();
            foreach ($results as $row) {
                $row = (array) $row;
                $contact = new Contact();
                $contact->exchangeArray($row);
                $contacts[] = $contact;
            }
            return $contacts;
        }

        return null;
    }

    /**
     *
     * @author Mienlv
     * @param \Home\Model\Contact $ob
     * @return \Zend\Db\Adapter\Driver\Pdo\Result
     */

    public function save($ob){
        $data = array(
            'name' => $ob->getName() ? : null,
            'storeId' => $ob->getStoreId(),
            'phone' => $ob->getPhone() ? : null,
            'email' => $ob->getEmail() ? : null,
            'file' => $ob->getFile() ? : null,
            'url' => $ob->getUrl() ? : null,
            'content' => $ob->getContent() ? : null,
        );

        $dbAdapter = $this->getDbAdapter();
        $dbSql = $this->getServiceLocator()->get('dbSql');

        if (!$ob->getId()) {
            $insert = $this->getDbSql()->insert(self::TABLE_NAME);
            $insert->values($data);
//            $query = $dbSql->buildSqlString($insert);
            $query = $dbSql->getSqlStringForSqlObject($insert);

            /* @var $results \Zend\Db\Adapter\Driver\Pdo\Result */
            $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
//            $ob->setId($results->getGeneratedValue());
        } else {
            $update = $this->getDbSql()->update(self::TABLE_NAME);
            $update->set($data);
            $update->where([
                'id' => (int) $ob->getId()
            ]);
//            $query = $dbSql->buildSqlString($update);
            $query = $dbSql->getSqlStringForSqlObject($update);
            $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        }

        return $results;
    }


}