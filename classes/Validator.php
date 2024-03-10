<?php

class Validator {
	
	public $name;
	public $startDate;
	public $endDate;
	public $duration;
	public $durationUnit;
	public $color;
	public $externalId;
	public $status;
	public $errors;

	public function __construct($data) {
		
		$this->errors = [];
		
		if (is_object($data)) {

			$vars = get_object_vars($this);

			foreach ($vars as $name => $value) {
				
				//validate only the passed data fields
				if ($name != 'errors') {
					
					// set values to validate 
					$this->$name = isset($data->$name) ? $data->$name : null;
					
					// construct the name of validating method
					$validatingMethodName = 'validate'. ucfirst($name);
					
					// call the appropriate validating method					
					$this->$validatingMethodName();
				}
			}
		}
	}
	
	/**
	 * Outputs validation errors as response.
	 *
	 * @return void.
	 */
		
	public function getErrors()
	{
		if (!empty($this->errors)) {
			
			//set unprocessable entity status code
			http_response_code(422);
			
			header("Content-Type: application/json");
			
			echo json_encode($this->errors);
			
			exit();
		}
		
	}
	
	/**
	 * Puts validation errors of field name into $errors array.
	 *
	 * @return bool.
	 */
	public function validateName()
	{
		//check if name is null because it is NOT NULL field in the database
		if (empty($this->name)) {
			
			$this->errors[] = ['name' => 'The field Name is required!'];
			
			return false;
		}
		
		//check if name contains more than 255 UNICODE symbols
		if (mb_strlen($this->name, 'UTF-8') > 255) {
			
			$this->errors[] = ['name' => 'The field Name must contain up to 255 characters!'];
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * Puts validation errors of field status into $errors array.
	 *
	 * @return bool.
	 */
	public function validateStatus()
	{
		//check status field requirements
		if (!is_null($this->status) && !in_array($this->status, ['NEW', 'PLANNED', 'DELETED'])) {
			
			$this->errors[] = ['status' => 'The field Status must be NEW or PLANNED, or DELETED!'];
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * Puts validation errors of field start_date into $errors array.
	 *
	 * @return bool.
	 */
	public function validateStartDate()
	{
		//check if start_date is null because it is NOT NULL field in the database
		if (empty($this->startDate)) {
			
			$this->errors[] = ['startDate' => 'The field Start date is required!'];
			
			return false;
		}
		
		// check if start_date is in iso date format
		if (!Helper::isIsoDate($this->startDate)) {
			
			$this->errors[] = ['startDate' => 'The field Start date must be date in iso format!'];
			
			return false;
        }
		
		return true;
	}
	
	/**
	 * Puts validation errors of field end_date into $errors array.
	 *
	 * @return bool.
	 */
	public function validateEndDate()
	{
		//check - end date can be null
		if (is_null($this->endDate))
		{
			return true;
			
		} else {
			
			//check - end date must be in iso8601 format
			if (!Helper::isIsoDate($this->endDate)) {
				
				$this->errors[] = ['endDate' => 'The field End date must be date in iso format!'];
				
				return false;
			}
			
			//check if start_date is later than end_date
			if (!Helper::compareDates($this->startDate, $this->endDate)) {
				
				$this->errors[] = [ 'end_date' => 'The End date must be later than Start date!'];
				
				return false;
			}
        }
		
		return true;
	}
	
	/**
	 * Validates field duration.
	 *
	 * @return bool.
	 */
	public function validateDuration()
	{
		return true;
	}
	
	/**
	 * Puts validation errors of field durationUnit into $errors array.
	 *
	 * @return bool.
	 */
	public function validateDurationUnit()
	{
		//check if durationUnit is one of the following values: HOURS, DAYS or WEEKS
		if (!is_null($this->durationUnit) && !in_array($this->durationUnit, ['HOURS', 'DAYS', 'WEEKS']))
		{
			$this->errors[] = ['durationUnit' => 'The field Duration unit must be HOURS or DAYS, or WEEKS!'];
			
			return false;
		}
		 return true;
	}
	
	/**
	 * Puts validation errors of field color into $errors array.
	 *
	 * @return bool.
	 */
	public function validateColor()
	{
		//color can be nullable
		if (is_null($this->color)) {
			
			return true;
			
		} 
		//check if color is a valid HEX color
		else if (preg_match('/^#[a-f0-9]{6}$/i', $this->color)) {
			
			return true;
		}
		
		$this->errors[] = ['color' => 'The field Color must be a valid HEX hour!'];
		
		return false;
	}
	
	/**
	 * Puts validation errors of field externalId into $errors array.
	 *
	 * @return bool.
	 */
	public function validateExternalId()
	{
		//check if length of externalId is greater than 255 
		if (mb_strlen($this->externalId, 'UTF-8') > 255) {
			
			$this->errors[] = ['externalId' => 'The field External Id must be up to 255 characters!'];
			
			return false;
		}
		
		return true;
	}
}