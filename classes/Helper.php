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
	
	public static function isIsoDate($value)
	{
		 try {
            new DateTime($value);
            return true;
        }
        catch (Exception $e)
        {
           return false;
        }
		return false;
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
			
			$startDateFormatted = DateTime::createFromFormat('m/d/Y H', $startDate);
			
			$endDateFormatted = DateTime::createFromFormat('m/d/Y H', $endDate);
			
			$difference = $startDateFormatted->diff($endDateFormatted)->days);
			
			if ($durationUnit === 'HOURS') {
				
				return $difference * 24;
				
			} else if ($durationUnit === 'WEEKS') {
				
				return round($difference / 7, 2);
			}
			
			return $difference;
		}
		
		return null;
	}
}