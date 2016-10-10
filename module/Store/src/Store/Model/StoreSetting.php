<?php
/**
 * @author      Chautm
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Store\Model;

use Base\Model\Base;

class StoreSetting extends Base
{
    const TYPE_PAGINATOR = 200; // set setting paginator for Controlers

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $updatedById;

    /**
     * @var string
     */
    protected $updatedDateTime;

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $updatedById
     */
    public function setUpdatedById($updatedById)
    {
        $this->updatedById = $updatedById;
    }

    /**
     * @return int
     */
    public function getUpdatedById()
    {
        return $this->updatedById;
    }

    /**
     * @param string $updatedDateTime
     */
    public function setUpdatedDateTime($updatedDateTime)
    {
        $this->updatedDateTime = $updatedDateTime;
    }

    /**
     * @return string
     */
    public function getUpdatedDateTime()
    {
        return $this->updatedDateTime;
    }

}