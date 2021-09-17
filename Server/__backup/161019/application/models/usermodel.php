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

  public function getLoginInfo($id, $pwd)
  {
    $sql = "SELECT
              *
            FROM
              user_tbl
            WHERE
              id='{$id}' AND pwd='{$pwd}'";
    $query = $this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
  }


}
