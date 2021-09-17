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
  public function doRegister($stationID, $stationNM, $centerID, $centerYN,
                              $x, $y, $regionName, $mobileNO, $districtDC)
  {
    $sql = "INSERT INTO
              bus_station_tbl(STATION_ID, STATION_NM, CENTER_ID, CENTER_YN,
                              X, Y, REGION_NAME, MOBILE_NO, DISTRICT_CD)
            VALUES
              (:STATION_ID, :STATION_NM, :CENTER_ID, :CENTER_YN,
                :X, :Y, :REGION_NAME, :MOBILE_NO, :DISTRICT_CD)";
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
                      ':DISTRICT_CD'  => $districtDC));
  }

  // 버스 정류소 삭제
  public function doRemove()
  {
    $sql = "DELETE FROM
              bus_station_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
  }

  // 버스 정류소 리스트 조회
  public function doSearchStationInfo()
  {
    $sql = "SELECT
              STATION_ID as id,
              STATION_NM as name
            FROM
              bus_station_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
    return $query->fetchAll();
  }

  public function doSearchStationInfoById($id)
  {
    $sql = "SELECT
              count(*) as total
            FROM
              bus_station_tbl
            WHERE
              STATION_ID='{$id}'";
    $query = $this->db->prepare($sql);
    $query->execute();
    return $query->fetch();
  }
}
