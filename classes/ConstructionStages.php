<?php

class ConstructionStages
{
	private $db;

	public function __construct()
	{
		$this->db = Api::getDb();
	}

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
		//die(json_encode($params));

		// Cut off last comma and append WHERE clause
		$stmt = substr($stmt,0,-2)." WHERE id = :id";
		// Store id for prepared statement
		$params['id'] = $id;
		// Prepare the query
		$stmt = $this->db->prepare($stmt);
		// Execute with parameters
		$stmt->execute($params);
		return $this->getSingle($id);
	}

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