<?php
namespace Base\Model;

class Ultility{
	public static function getFileExtension($filename) {
		if(!$filename) return '';
		$infos = explode('.', $filename);
		return $infos[count($infos) - 1];
	}
}