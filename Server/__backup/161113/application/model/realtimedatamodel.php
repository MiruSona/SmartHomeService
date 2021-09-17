<?php
class RealtimeDataModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  // 센서 데이터 조회
  public function getSensorData()
  {
    $sql = "SELECT
              type,
              sensor_val1 as sensorVal1,
              sensor_val2 as sensorVal2
            FROM
              realtime_data_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetchAll();
  }

  // 센서 데이터 조회(by Type)
  public function getSensorDataByType($type)
  {
    $sql = "SELECT
              sensor_val1 as sensorVal1,
              sensor_val2 as sensorVal2
            FROM
              realtime_data_tbl
            WHERE
              type = '{$type}'";
    $query = $this->db->prepare($sql);
    $query->execute();
    return $query->fetch();
  }
}
