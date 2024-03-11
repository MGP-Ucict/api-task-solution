<?php

/**
 *
 * @group 1. Construction stages management
 *
 * APIs for managing construction stages
 */
class ConstructionStages
{
	private $db;

	public function __construct()
	{
		$this->db = Api::getDb();
	}

	/**
	* Lists construction stages.
	*
	* @response
	* [
	*	{
	*		"id": 1,
	*		"name": "Pension Mühlbachtal 36",
	* 		"startDate": "2021-05-01T00:00:00Z",
	*		"endDate": null,
	*		"duration": null,
	*		"durationUnit": null,
	*		"color": null,
	*		"externalId": null,
	*		"status": "NEW"
	*	},
	*	{
	*		"id": 2,
	*		"name": "Reinigung VERO PB",
	*		"startDate": "2021-05-01T00:00:00Z",
	*		"endDate": null,
	*		"duration": null,
	*		"durationUnit": null,
	*		"color": null,
	*		"externalId": null,
	*		"status": "NEW"
	*		
	*	}
	* ]
	*/
	public function getAll()
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
		");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	* Shows a construction stage.
	* @queryParam id integer The identifier of the construction stage.
	* 
	* @response
	* {
	*	"id": 165,
	*	"name": "Stage1",
	*	"startDate": "2023-01-01T00:00:00Z",
	*	"endDate": "2024-03-31T10:08:00Z",
	*	"duration": 455.42,
	*	"durationUnit": "DAYS",
	*	"color": "#99FF70",
	*	"extenalId": "1234",
	*	"status": "NEW",
	* }
	*/
	public function getSingle($id)
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
			WHERE ID = :id
		");
		$stmt->execute(['id' => $id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Creates a constructio stage by input data
	*
	* @bodyParam name string The name of the construction stage. Validation: required
	* @bodyParam startDate string The start date of the construction stage in iso8601. Validation: required
	* @bodyParam endDate string The end date of the construction stage in iso8601.
	* @bodyParam durationUnit string The durationUnit of the construction stage.
	* @bodyParam color string The color of the construction stage in HEX format.
	* @bodyParam externalId string The externalId of the construction stage.
	* @bodyParam status string The status of the construction stage.
	* 
	* @response
	* {
	*	"id": 165,
	*	"name": "Stage1",
	*	"startDate": "2023-01-01T00:00:00Z",
	*	"endDate": "2024-03-31T10:08:00Z",
	*	"duration": 455.42,
	*	"durationUnit": "DAYS",
	*	"color": "#99FF70",
	*	"extenalId": "1234",
	*	"status": "NEW",
	* }
	*/
	public function post(ConstructionStagesCreate $data)
	{
		$stmt = $this->db->prepare("
			INSERT INTO construction_stages
			    (name, start_date, end_date, duration, durationUnit, color, externalId, status)
			    VALUES (:name, :start_date, :end_date, :duration, :durationUnit, :color, :externalId, :status)
			");
		$stmt->execute([
			'name' => $data->name,
			'start_date' => $data->startDate,
			'end_date' => $data->endDate,
			'duration' => $data->duration,
			'durationUnit' => $data->durationUnit,
			'color' => $data->color,
			'externalId' => $data->externalId,
			'status' => $data->status,
		]);
		return $this->getSingle($this->db->lastInsertId());
	}

	/**
	* Edits a construction stage by input data.
	*
	* @queryParam id integer The identifier of the construction stage.
	* @bodyParam name string The name of the construction stage. Validation: required
	* @bodyParam startDate string The start date of the construction stage in iso8601. Validation: required
	* @bodyParam endDate string The end date of the construction stage in iso8601.
	* @bodyParam durationUnit string The durationUnit of the construction stage.
	* @bodyParam color string The color of the construction stage in HEX format.
	* @bodyParam externalId string The externalId of the construction stage.
	* @bodyParam status string The status of the construction stage.
	*
	* @response
	*  {
	*	"id": 165,
	*	"name": "Stage1",
	*	"startDate": "2023-01-01T00:00:00Z",
	*	"endDate": "2024-03-31T10:08:00Z",
	*	"duration": 455.42,
	*	"durationUnit": "DAYS",
	*	"color": "#99FF70",
	*	"externalId": "231231241234432",
	*	"status": "PLANNED"
	*   }
	*/
	public function patch(ConstructionStagesEdit $data, $id)
	{	
		$stmt = "UPDATE construction_stages SET ";
		$params = [];
		foreach ($data as $key => $value) {
			if (in_array($key, ['startDate', 'endDate'])) {
				$key = Helper::camelToSnake($key);
			}
		    // Append a new SET key/value pair
		    $stmt .= "$key = :$key, ";
		    //Prepared statements
		    $params[$key] = $value;
		}

		// Cut off last comma and append WHERE clause
		$stmt = substr($stmt,0,-2)." WHERE id = :id";
		// Store id for prepared statement
		$params['id'] = $id;
		$stmt = $this->db->prepare($stmt);
		$stmt->execute($params);
		return $this->getSingle($id);
	}

	/**
	* Deletes a construction stage.
	* 
	* @queryParam id integer The identifier of the construction stage.
	* 
	* @response
	*  {
	*	"id": 165,
	*	"name": "Stage1",
	*	"startDate": "2023-01-01T00:00:00Z",
	*	"endDate": "2024-03-31T10:08:00Z",
	*	"duration": 455.42,
	*	"durationUnit": "DAYS",
	*	"color": "#99FF70",
	*	"externalId": "231231241234432",
	*	"status": "DELETED"
	*   }
	*/
	public function delete($id)
	{	
		$stmt = $this->db->prepare("
			UPDATE construction_stages
			SET status = :status
			WHERE ID = :id
		");
		$stmt->execute([
			'status' => 'DELETED',
			'id' => $id
		]);
		return $this->getSingle($id);
	}
}