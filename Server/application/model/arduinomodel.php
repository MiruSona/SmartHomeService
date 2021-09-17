<?php
class ArduinoModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  // 아두이노 정보 조회
  public function getArduinoInfo() {
    $sql = "SELECT
              idx,
              location
            FROM
              arduino_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetchAll();
  }
}
