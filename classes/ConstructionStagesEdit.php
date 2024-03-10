<?php

class ConstructionStagesEdit
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
					
					if (!empty($data->$name)) {
					
						$this->$name = $data->$name;
						
					} 
					// pass only not empty fields 
					else {
						
						unset($this->$name);
					}
				}
			}
		}
		
		//set computed value of duration
		$this->duration = Helper::calculate($this->startDate, $this->endDate, $this->durationUnit);
	}
}