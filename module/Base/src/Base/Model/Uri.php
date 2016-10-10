<?php

namespace Base\Model;
use Home\Model\DateBase;

class Uri
{
	/**
	 * @param Object $obj
	 */
	public static function slugify($obj) {
		switch ($obj) {
			case $obj instanceof \Product\Model\Category :
				$name = \Base\Model\Resource::slugify ( html_entity_decode($obj->getName()) );
				return "/c{$obj->getId()}/$name";
				break;
			case $obj instanceof \Product\Model\Product:
				$name = Resource::slugify(html_entity_decode($obj->getName()));
				return "p{$obj->getId()}/$name";
				break;
			case $obj instanceof \Article\Model\Article:
				$name = \Base\Model\Resource::slugify(html_entity_decode($obj->getName()));
				return "article/{$obj->getId()}-$name";
                break;
            case $obj instanceof \News\Model\Article:
                $name = \Base\Model\Resource::slugify(html_entity_decode($obj->getTitle()));
//                print_r($obj);die;
                if($obj->getStoreId() == 3){
                    if($obj->getCategoryId() == 3){
                        return "/blog/{$obj->getId()}/$name";
                    }else{
                        return "/profilo/{$obj->getId()}/$name";
                    }
                }else{
                    return "/news/{$obj->getId()}-$name";
                }
                break;
            case $obj instanceof \News\Model\Category:
                $name = \Base\Model\Resource::slugify(html_entity_decode($obj->getName()));
                return "/n{$obj->getId()}/$name";
                break;
			case $obj instanceof \Article\Model\Category:
				$name = \Base\Model\Resource::slugify(html_entity_decode($obj->getName()));
				return "article/c{$obj->getId()}-$name";

            case $obj instanceof \Home\Model\Page:
                $name = \Base\Model\Resource::slugify(html_entity_decode($obj->getName()));
                return "/page/{$obj->getId()}-$name";
				
		}
		return '';
	}

	/**
	 * @param Object $obj
	 */
	public static function getImgSrc($obj, $thumbnail = null) {
		switch ($obj) {
			case $obj instanceof \Admin\Model\Productc :
				if ($obj->getImage ()) {
					return '/images/product/category/'. $obj->getImage ();
				}
				break;
			case $obj instanceof \Admin\Model\Product :
				if ($obj->getImage ()) {
					return '/images/product/product/'. $obj->getImage ();
				}
				break;
			case $obj instanceof \Admin\Model\Articlec :
				if ($obj->getImage ()) {
					return '/images/article/category/'. $obj->getImage ();
				}
				break;
			case $obj instanceof \Admin\Model\Article :
				if ($obj->getImage ()) {
					return '/images/article/article/'. $obj->getImage ();
				}
				break;
            case $obj instanceof \News\Model\Article:
                $pathy = DateBase::toFormat($obj->getCreatedDateTime(), 'Y');
                $pathm = DateBase::toFormat($obj->getCreatedDateTime(), 'm');
                return '/uploads/article/'.$pathy.'/'.$pathm.'/'. $obj->getImage ();
                break;
			case $obj instanceof \Product\Model\Product :
				if ($obj->getImage ()) {
					return '/images/product/product/'. $obj->getImage ();
				}
				break;
            case $obj instanceof \Admin\Model\Media:
                $pathy = DateBase::toFormat($obj->getCreatedDateTime(), 'Y');
                $pathm = DateBase::toFormat($obj->getCreatedDateTime(), 'm');
                return '/uploads/media/'.$obj->getStoreId().'/'.$pathy.'/'.$pathm.'/'. $obj->getFileName();
                break;
            case $obj instanceof \Home\Model\Media:
                $pathy = DateBase::toFormat($obj->getCreatedDateTime(), 'Y');
                $pathm = DateBase::toFormat($obj->getCreatedDateTime(), 'm');
                return '/uploads/media/'.$obj->getStoreId().'/'.$pathy.'/'.$pathm.'/'. $obj->getFileName();
                break;
		}
		return '';
	}

	/**
	 * @param Object $obj
	 */
	public static function getSavePath($obj)
	{
		switch ($obj) {
			case $obj instanceof \Product\Model\Product :
				return MEDIA_PATH . '/product/product/';
			case $obj instanceof \Admin\Model\Product :
				return MEDIA_PATH . '/product/product/';
			case $obj instanceof \Admin\Model\Productc :
				return MEDIA_PATH . '/product/category/';
			case $obj instanceof \Admin\Model\Articlec :
				return MEDIA_PATH . '/article/category/';
			case $obj instanceof \Admin\Model\Article:
                $pathy = DateBase::toFormat($obj->getCreatedDateTime(), 'Y');
                $pathm = DateBase::toFormat($obj->getCreatedDateTime(), 'm');
                if($pathy && $pathm){
                    return UPLOAD_PATH . '/article/'.$pathy.'/'.$pathm;
                }else{
                    return UPLOAD_PATH . '/details/';
                }
            case $obj instanceof \Home\Model\Contact :
                return MEDIA_PATH . '/file/guest/';

 			case $obj instanceof \Admin\Model\Media :
                $pathy = DateBase::toFormat($obj->getCreatedDateTime(), 'Y');
                $pathm = DateBase::toFormat($obj->getCreatedDateTime(), 'm');
//                chmod(UPLOAD_PATH . '/media/'.$obj->getStoreId(), 0777);
//                chmod(UPLOAD_PATH . '/media/'.$obj->getStoreId().'/'.$pathy, 0777);
//                chmod(UPLOAD_PATH . '/media/'.$obj->getStoreId().'/'.$pathy.'/'.$pathm, 0777);

                return UPLOAD_PATH . '/media/'.$obj->getStoreId().'/'.$pathy.'/'.$pathm;
//                return UPLOAD_PATH . '/media/10/2016/09';

        }
		return '';
	}
}