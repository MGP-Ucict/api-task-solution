<?php

class ConstructionStagesCreate
{
	public $name;
	public $startDate;
	public $endDate;
	public $duration;
	public $durationUnit;
	public $color;
	public $externalId;
	public $status;

	public function __construct($data) {
		
		Validator($data);
		
		Validator::getErrors();

		if(is_object($data)) {

			$vars = get_object_vars($this);

			foreach ($vars as $name => $value) {

				if (isset($data->$name)) {

					$this->$name = $data->$name;
		
				}
			}
		}

		$this->setDefaultStatus();
		
		$this->setDefaultDurationUnit();
		
		$this->duration = Helper::calculate($this->startDate, $this->endDate, $this->durationUnit);
	}
	
	
	public function setDefaultStatus()
	{
		
		if (empty($this->status)) {
			
			$this->status = 'NEW';
		}
	}
	
	
	public function setDefaultDurationUnit()
	{
		if (empty($this->durationUnit)) {
			
			$this->durationUnit = 'DAYS';
		}
	}
}