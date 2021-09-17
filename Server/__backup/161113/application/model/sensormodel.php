<?php
class SensorModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  // 센서 정보 조회
  public function getSensorInfo() {
    $sql = "SELECT
              idx,
              type,
              pin,
              arduino_idx as arduinoIdx
            FROM
              sensor_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetchAll();
  }



}
