<?php

namespace Base\Model;

class Format{

    protected $options = array(
    	'precisions'
    );

    /**
     * convert to number
     */
    public function toNumber($number, $viewDecimal = false) {
        if ($number == 0) return $number;
    	$number = round($number, 2);
        if(!$number) {
            return '';
        }
        $decimal = '';
        if(strpos($number, ".")) {
			list($number, $decimal) = explode(".", $number);
        }
        $result = '';
        $sign = '';
        if($number < 0) {
            $sign = '-';
            $number = $number + ($number * (-2));
        }
        while(strlen($number) > 3) {
            $result = '.' . substr($number, strlen($number)-3, 3) . $result;
            $number = substr($number, 0, strlen($number)-3);
        }
        $return = $sign . $number . $result;
        if($decimal) {
        	$return .= ','. $decimal;
        }
        if($viewDecimal && !$decimal) {
        	$return .= ',00';
        }
        return $return;
    }
    public function toNumberFormat ( $number , $decimals = 0 , $dec_point = ',' , $thousands_sep = '.' ){
    	if(!$number){
    		return  '';
    	}
		return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     *
     * @param int $number
     * @param int $divisibleNumber
     * @return number
     */
    public function roundDivisible($number, $divisibleNumber) {
    	if(($mod = $number % $divisibleNumber) != 0) {
    		$number = $number + $divisibleNumber - $mod;
    	}
    	return $number;
    }

    /**
     *
     * @param string $str
     * @return string
     */
    public static function removeVietnamese($str) {
	    $accents = array(
	        "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
	        "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề",
	        "ế","ệ","ể","ễ",
	        "ì","í","ị","ỉ","ĩ",
	        "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ",
	        "ờ","ớ","ợ","ở","ỡ",
	        "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
	        "ỳ","ý","ỵ","ỷ","ỹ",
	        "đ",
	        "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
	        "Ằ","Ắ","Ặ","Ẳ","Ẵ",
	        "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
	        "Ì","Í","Ị","Ỉ","Ĩ",
	        "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ",
	        "Ờ","Ớ","Ợ","Ở","Ỡ",
	        "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
	        "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
	        "Đ"
	    );
	    $remove = array(
	        "a","a","a","a","a","a","a","a","a","a","a",
	        "a","a","a","a","a","a",
	        "e","e","e","e","e","e","e","e","e","e","e",
	        "i","i","i","i","i",
	        "o","o","o","o","o","o","o","o","o","o","o","o",
	        "o","o","o","o","o",
	        "u","u","u","u","u","u","u","u","u","u","u",
	        "y","y","y","y","y",
	        "d",
	        "A","A","A","A","A","A","A","A","A","A","A","A",
	        "A","A","A","A","A",
	        "E","E","E","E","E","E","E","E","E","E","E",
	        "I","I","I","I","I",
	        "O","O","O","O","O","O","O","O","O","O","O","O",
	        "O","O","O","O","O",
	        "U","U","U","U","U","U","U","U","U","U","U",
	        "Y","Y","Y","Y","Y",
	        "D"
	    );
    	return str_replace($accents, $remove, $str);
	}

	public static function removeSpecialCharacters($str) {
	    $accents = array(
	        "&amp;",
	    );
	    $remove = array(
	        "and",
	    );
    	return str_replace($accents, $remove, $str);
	}
}