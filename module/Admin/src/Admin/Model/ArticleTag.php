<?php
namespace Admin\Model;
use Base\Model\Base;
 
class ArticleTag extends Base{

 	protected $articleId;
 	protected $tagId;

    /**
     * @param mixed $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $tagId
     */
    public function setTagId($tagId)
    {
        $this->tagId = $tagId;
    }

    /**
     * @return mixed
     */
    public function getTagId()
    {
        return $this->tagId;
    }


}
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 