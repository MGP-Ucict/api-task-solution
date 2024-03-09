<?php

class Helper {

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
	
	public static function isIsoDate($date)
	{ 
		$d = DateTime::createFromFormat('Y-m-d\TH:i:sZ', $date);
		
		return $d && $d->format('Y-m-d\TH:i:s\Z') == $date;
	}
	
	
	public static function compareDates($startDate, $endDate)
	{
		if (strtotime($startDate) < strtotime($endDate)) {
			return true;
		}
		return false;
	}
	
	public static function calculate($startDate, $endDate, $durationUnit)
	{
		
		if (!is_null($endDate)) {
			
			//convert date string to DateTime object
			$startDate = new DateTime($startDate);
			
			//convert date string to DateTime object
			$endDate = new DateTime($endDate);
		
			//get DateInterval object with difference between $endDate and $startDate
			$differenceObj = $startDate->diff($endDate);
			
			//get years
			$years = $differenceObj->y;
			
			//get months reminder
			$months = $differenceObj->m;
			
			//get hours reminder
			$hours = $differenceObj->h;
		
			//get days reminder
			$days = $differenceObj->d;
			
			//calculate whole hours difference
			// I assume,  that 1 month has 30 days
			// 1 year has 12 months
			$differenceInHours = $hours + ($days * 24) + ($months * 30 * 24) + ($years * 12 * 30 * 24);
			
			// rounded in 2 digits after decimal point - 1 h is 0.04 of 1 day, because the precision is of whole hours
			// and minutes are ignored
			$differenceInDays = round($differenceInHours / 24, 2);
			
			if ($durationUnit === 'DAYS') {
				
				return $differenceInDays;
				
			}  else if ($durationUnit === 'HOURS') {
				
				return $differenceInHours;
			}
			else if ($durationUnit === 'WEEKS') {
				
				// rounded in 4 digits after decimal point - 1 h is 0.006 of 1 week, because the precision is of whole hours
				// and minutes are ignored
				return round($differenceInDays / 7, 3);
			}
		}
		
		return null;
	}
}