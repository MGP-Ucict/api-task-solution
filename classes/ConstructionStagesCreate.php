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
		
		$validator = new Validator($data);
		
		$validator->getErrors();
	
		if(is_object($data)) {

			$vars = get_object_vars($this);

			foreach ($vars as $name => $value) {

				if (isset($data->$name)) {

					$this->$name = $data->$name;
		
				}
			}
		}
		//set default value durationUnit
		$this->setDefaultDurationUnit();
		
		//set default value to status
		$this->setDefaultStatus();
		 
		 //set computed value to duration
		$this->duration = Helper::calculate($this->startDate, $this->endDate, $this->durationUnit);
	}
	
	/**
	 * Sets default value DAYS to durationUnit.
	 *
	 * @return void.
	 */
	public function setDefaultDurationUnit()
	{
		if (empty($this->durationUnit)) {
			
			$this->durationUnit = 'DAYS';
		}
	}
	
	/**
	 * Sets default value NEW to status.
	 *
	 * @return void.
	 */
	public function setDefaultStatus()
	{
		if (empty($this->status)) {
			
			$this->status = 'NEW';
		}
	}
}