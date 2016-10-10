<?php

namespace Base\Model;

class Resource {

	/**
	 * @param string $controller
	 * @return string
	 */
	public static function getControllerName($controller) {
		$controllerArr = explode('\\', $controller);
		return strtolower(str_replace("Controller", "", array_pop($controllerArr)));
	}

	/**
	 * @param string $namespace
	 * @return string
	 */
	public static function getModuleName($namespace) {
		$moduleArr = explode('\\', $namespace);
		return strtolower(array_shift($moduleArr));
	}

	/**
	 * remove vietnamese signs
	 * @param string $text
	 * @return string
	 */
	public static function removeSigns($text) {
		if(!$text) {
			return "";
		}
		$vnSigns = array(
			'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			'd' => 'đ',
			'D' => 'Đ',
			'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'E' => 'É|É|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			'i' => 'í|ì|ỉ|ĩ|ị',
			'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
			'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
			'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
		);
		foreach($vnSigns as $unsign => $signs) {
			$text = preg_replace("/($signs)/", $unsign, $text);
		}
		return $text;
	}

	/**
	 * @param string $text
	 * @param boolean $toLower
	 * @return string
	 */
	static public function slugify($text, $toLower = true) {
		if (empty($text)) {
			return '';
		}
		$text = trim(self::removeSigns($text));
		$text = preg_replace('/[^a-zA-Z0-9\s.?!]/', '', $text);
		$text = str_replace(array(' - ', ' ', '&', '--'), '-', $text);

		if($toLower) {
			$text = strtolower($text);
		}
		return $text;
	}

	const CHARSET_NUMERIC 		= "0123456789";
	const CHARSET_ALPHABET 		= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	const CHARSET_ALPHANUMERIC 	= "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	/**
	 * @param string $charset
	 * @param int $length
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public static function generateRandom($charset, $length = 6, $prefix = null, $suffix = null) {
		$code = "";
		for ($i=0; $i<$length; $i++) {
			$code .= $charset[rand(0, strlen($charset) - 1)];
		}
		if($prefix) {
			$code = $prefix . $code;
		}
		if($suffix) {
			$code = $code . $suffix;
		}
		return $code;
	}
}