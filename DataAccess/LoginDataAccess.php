<?php

class LoginDataAccess {
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    public function SelectBySessionKey($SessionKey)
    {
        return $this->db->Query("SELECT * FROM users WHERE sessionstr = '{$SessionKey}'");
    }

    public function SelectByUsername($Username)
    {
        return $this->db->Query("SELECT * FROM users WHERE username = '{$Username}'");
    }

    public function DoesExist($Username)
    {
        $result = $this->db->Query("SELECT EXISTS(SELECT * FROM users WHERE username = '{$Username}') AS does_exist");
        if ($row = $result->fetch_array())
        {
            return boolval($row["does_exist"]);
        }
        return false;
    }

    /** @param LoginModel $LoginModel
     * @return int
     */
    public function Insert($LoginModel)
    {
        $this->db->Query("INSERT INTO users
        (username,
        password)

        VALUES

        ('".$LoginModel->Username."',
        '".$LoginModel->Password."'
        )");

        return $this->db->conn->insert_id;
    }

    /** @param LoginModel $LoginModel */
    public function Update($LoginModel)
    {
        if (isset($LoginModel) && !is_null($LoginModel->UserId) && is_numeric($LoginModel->UserId) && $LoginModel->UserId > -1) {
            $this->db->Query("UPDATE users
            SET username = '" . $LoginModel->Username . "',
            password = '" . $LoginModel->Password . "',
            sessionstr = '" . $LoginModel->SessionString . "'
            WHERE
            userid = '" . $LoginModel->UserId . "'");
        }
    }

    public function Delete($UserId)
    {
        if(is_numeric($UserId)) {
            $this->db->Query("DELETE FROM users
          WHERE userid = '$UserId' ");
        }
    }
}