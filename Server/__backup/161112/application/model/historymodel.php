<?php
class HistoryModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  public function getHistoryInfoBySensor($sensorIdx)
  {
    $sql = "SELECT
              sensor_idx as sensorIdx,
              mac_address as macAddress,
              date,
              type,
              sensor_val1 as sensorVal1,
              sensor_val2 as sensorVal2
            FROM
              history_tbl
            WHERE
              sensor_idx={$sensorIdx}";
    $query = $this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
  }

  public function getHistoryInfoByMac($macAddress)
  {

  }

  public function getHistoryInfoByPeriod($fromDate, $toDate)
  {
    $sql = "SELECT
              sensor_idx as sensorIdx,
              mac_address as macAddress,
              date,
              type,
              sensor_val1 as sensorVal1,
              sensor_val2 as sensorVal2
            FROM
              history_tbl
            WHERE
              date>={$fromDate} AND date<={$toDate}+1";
    $query = $this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
  }

}
