<?php
class BusStationModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  // 버스 정류소 등록
  public function register($stationID, $stationNM, $centerID, $centerYN,
                           $x, $y, $regionName, $mobileNO, $districtDC, $serial) {
    $sql = "INSERT INTO
              favorite_bus_stn_tbl(STATION_ID, STATION_NM, CENTER_ID, CENTER_YN,
                                    X, Y, REGION_NAME, MOBILE_NO, DISTRICT_CD, pi_serial_no)
            VALUES
              (:STATION_ID, :STATION_NM, :CENTER_ID, :CENTER_YN,
                :X, :Y, :REGION_NAME, :MOBILE_NO, :DISTRICT_CD, :pi_serial_no)";
    $query = $this->db->prepare($sql);
    $query->execute(array(
                      ':STATION_ID'   => $stationID,
                      ':STATION_NM'   => $stationNM,
                      ':CENTER_ID'    => $centerID,
                      ':CENTER_YN'    => $centerYN,
                      ':X'            => $x,
                      ':Y'            => $y,
                      ':REGION_NAME'  => $regionName,
                      ':MOBILE_NO'    => $mobileNO,
                      ':DISTRICT_CD'  => $districtDC,
                      ':pi_serial_no' => $serial));
  }

  // 버스 정류소 삭제
  public function remove() {
    $sql = "DELETE FROM
              favorite_bus_stn_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
  }

  // 버스 정류소 조회
  public function getBusStnInfo() {
    $sql = "SELECT
              STATION_ID,
              STATION_NM,
              CENTER_ID,
              CENTER_YN,
              X,
              Y,
              REGION_NAME,
              MOBILE_NO,
              DISTRICT_CD
            FROM
              favorite_bus_stn_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetchAll();
  }
}
