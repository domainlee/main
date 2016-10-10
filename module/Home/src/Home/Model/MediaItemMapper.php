<?php

namespace Home\Model;

use Base\Mapper\Base;

//use Home\Model\Base;

use Home\Model\BaseMapper;

Class MediaItemMapper extends Base
{
    /**
     * @var string
     */
    protected $tableName = 'media_item';

    CONST TABLE_NAME = 'media_item';

    /**
     * @param $item int
     * @return Home|null
     */
    public function get($item)
    {
        if(!$item->getItemId()){
            return null;
        }
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getDbSql();
        $select = $dbSql->select(['mi' => \Home\Model\MediaItemMapper::TABLE_NAME]);
        $select->where(['mi.itemId' => $item->getItemId()]);
        $select->where(['mi.type' => $item->getType()]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
        $rs = [];
        $fileItem = [];

        if ($result->count()) {
            foreach ($result as $row){
                $row = (array)$row;
                $mediaItem = new \Home\Model\MediaItem();
                $mediaItem->exchangeArray($row);
                $rs[] = $mediaItem;
                $fileItem[] = $row['fileItem'];
            }
            $select = $dbSql->select(['m' => \Home\Model\MediaMapper::TABLE_NAME]);
            $select->where(['m.id' => $fileItem]);
            $selectStr = $dbSql->getSqlStringForSqlObject($select);
            $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
            if($results->count()){
                foreach ($results as $r){
                    $r = (array) $r;
                    $media = new \Home\Model\Media();
                    $media->exchangeArray($r);
                    $medias[$r['id']] = $media;
                }
            }
            if(count($rs)){
                foreach ($rs as $mi){
                    if(isset($medias[$mi->getFileItem()])){
                        $mi->addOption('media', $medias[$mi->getFileItem()]);
                    }
                }
            }
            return $rs;
        }
        return null;
    }

}