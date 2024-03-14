<?php

class Helper {

	/**
	 * Converts camelCase string to snake_case string.
	 *
	 * @param string $camelCase
	 *
	 * @return string.
	 */
	public static function camelToSnake($camelCase)
	{
		$result = ''; 
	  
		for ($i = 0; $i < strlen($camelCase); $i++) { 
			$char = $camelCase[$i]; 
	  
			if (ctype_upper($char)) { 
				$result .= '_' . strtolower($char); 
			} else { 
				$result .= $char; 
			} 
		} 
	  
		return ltrim($result, '_'); 
	}
	
	/**
	 * Check if date string is in iso8601 format.
	 *
	 * @param string $date
	 *
	 * @return bool.
	 */
	public static function isIsoDate($date)
	{ 
		$d = DateTime::createFromFormat('Y-m-d\TH:i:sZ', $date);
		
		return $d && $d->format('Y-m-d\TH:i:s\Z') == $date;
	}
	
	/**
	 * Checks whether $endDate is later than $startDate.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 *
	 * @return bool.
	 */
	public static function compareDates($startDate, $endDate)
	{
		if (strtotime($startDate) < strtotime($endDate)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Computes duration of construction stage.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param string $durationUnit
	 *
	 * @return float|null.
	 */
	public static function calculate($startDate, $endDate, $durationUnit)
	{
		
		if (!is_null($endDate)) {
			
			// compute difference and round to whole hours
			$differenceInHours = round((strtotime($endDate) - strtotime($startDate))/3600, 0);
			
			if ($durationUnit === 'HOURS') {
				
				return $differenceInHours;
			}
			else if ($durationUnit === 'DAYS') {
				
				// rounded in 3 digits after decimal point - 1 h is 0.042 of 1 day, because the precision is hours
				// and minutes are ignored
				 return  round($differenceInHours / 24, 3);
				
			}
			else if ($durationUnit === 'WEEKS') {
				
				// rounded in 3 digits after decimal point - 1 h is 0.006 of 1 week, because the precision is hours
				// and minutes are ignored
				return round($differenceInHours / (24 * 7) , 3);
			}
		}
		
		// if durationUnit is not one of HOURS, DAYS or WEEKS or end_date is null
		return null;
	}
}