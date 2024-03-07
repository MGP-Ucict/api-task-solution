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
		
		if(is_object($data)) {

			$vars = get_object_vars($this);

			foreach ($vars as $name => $value) {

				if (isset($data->$name) && !empty($data->$name)) {
					
					$validatingMethodName = 'validate'. ucfirst($name);
					
					$this->$name = $data->$name;
						
					$this->$validatingMethodName();
					
				}
			}
		}
	}
	
	public function getErrors()
	{
		if (!empty($this->errors)) {
			
			http_response_code(422);
			
			throw new Exception(json_encode($this->errors));
		}
		
	}
	
	public function validateName()
	{
		
		if (is_null($this->name)) {
			
			$this->errors[] = ['name' => 'The field name is required!'];
			
			return false;
		}
		if (mb_strlen($this->name, 'utf8') > 255) {
			
			$this->errors[] = ['name' => 'The field name must contain up to 255 characters!'];
			
			return false;
		}
		
		return true;
	}
	
	public function validateStatus()
	{
		if (!in_array($this->status, ['NEW', 'PLANNED', 'DELETED'])) {
			
			$this->errors[] = ['status' => 'The field status must be NEW or PLANNED, or DELETED!'];
			
			return false;
		}
		
		return true;
	}
	
	public function validateStartDate()
	{
		if (is_null($this->startDate)) {
			
			$this->errors[] = ['start_date' => 'The field start_date is required!'];
		}

		if (!Helper::isIsoDate($this->startDate)) {
			
			$this->errors[] = ['start_date' => 'The field start_date must be date in iso format!'];
			
			return false;
        }
		
		return true;
	}
	
	public function validateEndDate($value)
	{
		if (is_null($value))
		{
			return true;
			
		} else {
			
			if (!Helper::isIsoDate($this->endDate)) {
				
				$this->errors[] = ['end_date' => 'The field end_date must be date in iso format!'];
				
				return false;
			}
			
			if (!Helper::compareDates($this->startDate, $this->endDate)) {
				
				$this->errors[] = [ 'end_date' => 'The end_date must be later than start_date!'];
				
				return false;
			}
        }
		
		return true;
	}
	
	public function validateDuration()
	{
		return true;
	}
	
	public function validateDurationUnit()
	{
		if (!in_array($this->durationUnit, ['HOURS', 'DAYS', 'WEEKS']))
		{
			$this->errors[] = ['durationUnit' => 'The field durationUnit must be HOURS or DAYS, or WEEKS!'];
			
			return false;
		}
		 return true;
	}
	
	public function validateColor()
	{
		if (is_null($this->color)) {
			
			return true;
			
		} else if (preg_match('/^#[a-f0-9]{6}$/i', $this->color)) {
			
			return true;
		}
		
		$this->errors[] = ['color' => 'The field color must be a valid HEX hour!'];
		
		return false;
	}
	
	public function validateExternalId()
	{
		if (strlen($this->externalId) > 255) {
			
			$this->errors[] = ['externalId' => 'The field externalId must be up to 255 characters!'];
			
			return false;
		}
		
		return true;
	}
}