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

  // 반경 조회
  public function getRadius()
  {
    $sql = "SELECT
              radius
            FROM
              pi_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // 좌표 조회
  public function getCoord()
  {
    $sql = "SELECT
              x,
              y
            FROM
              pi_tbl";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }









  // 계정정보 조회
  public function doGetUserInfo($id)
  {
    $sql = "SELECT
              id as userId,
              name as userName,
              birth as userBirth,
              phone_no as userPhoneNo,
              address as userAddress
            FROM
              user_tbl
            WHERE
              id='{$id}'";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }


  // ID 체크
  public function doCheckID($id)
  {
    $sql = "SELECT
              count(*) as total
            FROM
              user_tbl
            WHERE
              id='{$id}'";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // PWD 체크
  public function doCheckPWD($id, $pwd)
  {
    $sql = "SELECT
              count(*) as total
            FROM
              user_tbl
            WHERE
              id='{$id}' AND pwd='{$pwd}'";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // 주소 체크
  public function doCheckAddr($id)
  {
    $sql = "SELECT
              address
            FROM
              user_tbl
            WHERE
              id='{$id}'";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // 회원가입
  public function doRegister($id, $pwd, $name, $birth, $phoneNo, $address)
  {
    // 각 입력값의 태그 제거
    $id       = strip_tags($id);
    $pwd      = strip_tags($pwd);
    $name     = strip_tags($name);
    $birth    = strip_tags($birth);
    $phoneNo  = strip_tags($phoneNo);
    $address  = strip_tags($address);

    $sql = "INSERT INTO
              user_tbl(id, pwd, name, birth, phone_no, address)
            VALUES
              (:id, :pwd, :name, :birth, :phone_no, :address)";
    $query = $this->db->prepare($sql);
    $query->execute(array(
                      ':id'       => $id,
                      ':pwd'      => $pwd,
                      ':name'     => $name,
                      ':birth'    => $birth,
                      ':phone_no' => $phoneNo,
                      ':address'  => $address));
  }
}
