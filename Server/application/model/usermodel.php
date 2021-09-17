<?php
class UserModel
{
  function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('데이터베이스 연결에 오류가 발생했습니다.');
		}
	}

  // 계정정보 조회
  public function getUserInfo($id) {
    $sql = "SELECT
              id,
              name,
              address,
              email,
              phone_no
            FROM
              user_tbl
            WHERE
              id='{$id}'";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetch();
  }

  // 관리자 계정 비밀번호 초기화
  public function resetUser($pwd) {
    $sql = "UPDATE
              user_tbl
            SET
              pwd = :pwd
            WHERE
              id = 'admin'";
    $query = $this->db->prepare($sql);
    $query->execute(array(':pwd'=> $pwd));
  }


  // ID 체크
  public function checkID($id)
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
  public function checkPWD($id, $pwd)
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

  // 해당 이메일로 등록된 유저 조회
  public function getUserInfoByEmail($email) {
    $sql = "SELECT
              id,
              pwd,
              email
            FROM
              user_tbl
            WHERE
              email='{$email}'";
    $query = $this->db->prepare($sql);
    $query->execute();
		return $query->fetchAll();
  }

  // 회원 정보수정
  public function updateUserInfo($id, $pwd, $name, $address, $email, $phoneNo)
  {
    // 각 입력값의 태그 제거
    $id       = strip_tags($id);
    $pwd      = strip_tags($pwd);
    $name     = strip_tags($name);
    $address  = strip_tags($address);
    $email    = strip_tags($email);
    $phoneNo  = strip_tags($phoneNo);

    $sql = "UPDATE
              user_tbl
            SET
              pwd = :pwd,
              name = :name,
              address = :address,
              email = :email,
              phone_no = :phone_no
            WHERE
              id = :id";
    $query = $this->db->prepare($sql);
    $query->execute(array(
                      ':id'       => $id,
                      ':pwd'      => $pwd,
                      ':name'     => $name,
                      ':address'  => $address,
                      ':email'    => $email,
                      ':phone_no'  => $phoneNo));
  }
}
