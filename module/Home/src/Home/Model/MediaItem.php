<?php
/**
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Home\Model;

use Base\Model\Base;

class MediaItem extends Base
{
    const FILE_ARTICLE = 1;
    const FILE_PRODUCT = 2;
    const FILE_CATEGORY_PRODUCT = 3;

    protected $type;
    protected $fileItem;
    protected $itemId;
    protected $sort;

    /**
     * @param mixed $fileItem
     */
    public function setFileItem($fileItem)
    {
        $this->fileItem = $fileItem;
    }

    /**
     * @return mixed
     */
    public function getFileItem()
    {
        return $this->fileItem;
    }

    /**
     * @param mixed $itemId
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }



}