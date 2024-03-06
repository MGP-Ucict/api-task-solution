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
}