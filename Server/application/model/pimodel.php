<?php
class PiModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  // 파이 정보 조회 (+ 주소정보)
  public function getPiInfo() {
    $sql = "SELECT
              A.serial_no as serialNo,
              A.x,
              A.y,
              A.radius,
              B.address
            FROM
              pi_tbl A,
              user_tbl B
            WHERE
              B.id='admin'";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // 반경 조회
  public function getRadius() {
    $sql = "SELECT
              radius
            FROM
              pi_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // 좌표 조회
  public function getCoord() {
    $sql = "SELECT
              x,
              y
            FROM
              pi_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // 정보 수정
  public function updatePiInfo($serial, $x, $y, $radius) {
    // 각 입력값의 태그 제거
    $serial = strip_tags($serial);
    $x      = strip_tags($x);
    $y      = strip_tags($y);
    $radius = strip_tags($radius);


    $sql = "UPDATE
              pi_tbl
            SET
              -- serial_no = :serial_no,
              x = :x,
              y = :y,
              radius = :radius";
    $query = $this->db->prepare($sql);
    $query->execute(array(
                      // ':serial_no'  => $serial,
                      ':x'          => $x,
                      ':y'          => $y,
                      ':radius'     => $radius));
  }
}
