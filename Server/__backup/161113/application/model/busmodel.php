<?php
class BusModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  // 버스 등록
  public function doRegister($routeID, $routeNM, $routeTP, $stStaID, $stStaNM,
                             $stStaNO, $edStaID, $edStaNM, $edStaNO, $upFirstTime,
                             $upLastTime, $downFirstTime, $downLastTime, $peekAlloc,
                             $nPeekAlloc, $companyID, $companyNM, $telNO, $regionName, $districtCD)
  {
    $sql = "INSERT INTO
              bus_tbl(ROUTE_ID, ROUTE_NM, ROUTE_TP, ST_STA_ID, ST_STA_NM,
                      ST_STA_NO, ED_STA_ID, ED_STA_NM, ED_STA_NO, UP_FIRST_TIME,
                      UP_LAST_TIME, DOWN_FIRST_TIME, DOWN_LAST_TIME, PEEK_ALLOC, NPEEK_ALLOC,
                      COMPANY_ID, COMPANY_NM, TEL_NO, REGION_NAME, DISTRICT_CD)
            VALUES
              (:ROUTE_ID, :ROUTE_NM, :ROUTE_TP, :ST_STA_ID, :ST_STA_NM,
               :ST_STA_NO, :ED_STA_ID, :ED_STA_NM, :ED_STA_NO, :UP_FIRST_TIME,
               :UP_LAST_TIME, :DOWN_FIRST_TIME, :DOWN_LAST_TIME, :PEEK_ALLOC, :NPEEK_ALLOC,
               :COMPANY_ID, :COMPANY_NM, :TEL_NO, :REGION_NAME, :DISTRICT_CD)";
    $query = $this->db->prepare($sql);
    $query->execute(array(
                      ':ROUTE_ID' => $routeID,
                      ':ROUTE_NM' => $routeNM,
                      ':ROUTE_TP' => $routeTP,
                      ':ST_STA_ID' => $stStaID,
                      ':ST_STA_NM' => $stStaNM,
                      ':ST_STA_NO' => $stStaNO,
                      ':ED_STA_ID' => $edStaID,
                      ':ED_STA_NM' => $edStaNM,
                      ':ED_STA_NO' => $edStaNO,
                      ':UP_FIRST_TIME' => $upFirstTime,
                      ':UP_LAST_TIME' => $upLastTime,
                      ':DOWN_FIRST_TIME' => $downFirstTime,
                      ':DOWN_LAST_TIME' => $downLastTime,
                      ':PEEK_ALLOC' => $peekAlloc,
                      ':NPEEK_ALLOC' => $nPeekAlloc,
                      ':COMPANY_ID' => $companyID,
                      ':COMPANY_NM' => $companyNM,
                      ':TEL_NO' => $telNO,
                      ':REGION_NAME' => $regionName,
                      ':DISTRICT_CD' => $districtCD));
  }

  // 버스 삭제
  public function doRemove()
  {
    $sql = "DELETE FROM
              bus_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
  }

  // 노선번호 조회
  public function doSearchRouteNM($routeId)
  {
    $sql = "SELECT
              ROUTE_NM as routeNM
            FROM
              bus_tbl
            WHERE
              ROUTE_ID='{$routeId}'";
    $query = $this->db->prepare($sql);
    $query->execute();
    return $query->fetch();
  }
}
