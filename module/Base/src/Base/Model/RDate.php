<?php
namespace Base\Model;
use DateTime;

class RDate extends DateTime
{
	protected $days = array(
		'Monday' => 'T.Hai',
		'Tuesday' => 'T.Ba',
		'Wednesday' => 'T.Tư',
		'Thursday' => 'T.Năm',
		'Friday' => 'T.Sáu',
		'Saturday' => 'T.Bảy',
		'Sunday' => 'CN',
	);
	public function dateToString($date = null, $format = null)
	{
		$date = $this->createFromFormat('Y-m-d', $date?: $this->format('Y-m-d'));
		if(!$date) return '';
		$format = $format?: 'd/m/Y';
		return $date->format($format);
	}

	public function dateTimeToString($date = null, $format = null)
	{
		$date = $this->createFromFormat('Y-m-d H:i:s', $date?: $this->format('Y-m-d H:i:s'));
		if($date) {
			return $date->format($format ?: 'H:i:s d/m/Y');
		}
		return "";
	}

	public static function getCurrentDate($common = false)
	{
		if (!$common) return date('Y-m-d');
        return date('d/m/Y');
	}

	public static function getCurrentDatetime()
	{
		return date('Y-m-d H:i:s');
	}

	public function toCommonDate($date = null)
	{
		$date = $this->createFromFormat('d/m/Y', $date?: $this->format('d/m/Y'));
		if($date) {
			return $date->format('Y-m-d');
		}
		return '';
	}

	public function toCommonDateTime($date = null)
	{
		$date = $this->createFromFormat('H:i:s d/m/Y', $date);
		return $date->format('Y-m-d H:i:s');
	}

	public static function getFirstDayOfMonth($common = false)
	{
		$date = new DateTime();
		if($date) {
			$date->setDate(date('Y'), date('m'), 1);
			if (!$common) return $date->format('Y-m-d');
            return $date->format('d/m/Y');
		}
		return '';
	}

	public static function getLastDayOfMonth($common = false)
	{
		$date = new DateTime();
		if($date) {
			$date->setDate(date('Y'), date('m') + 1, 1);
			$date->sub(new DateInterval("P1D"));
			if (!$common) return $date->format('Y-m-d');
            return $date->format('d/m/Y');
		}
		return '';
	}

	public function toDayOfWeek($date = null, $format='d/m/Y')
	{
		$date = $this->createFromFormat($format, $date?: $this->format($format));
		if($date) {
			return $this->days[$date->format('l')];
		}
		return '';
	}
}