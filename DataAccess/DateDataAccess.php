<?php

class DateDataAccess {
    private $db;
    
    function __construct()
    {
        $this->db = new db();
    }

    public function Select()
    {
        return $this->db->Query("SELECT * FROM dates WHERE userid = ".Utility::GetLoggedInUser()->UserId." ORDER BY id");
    }

    public function SelectById($ID)
    {
        return $this->db->Query("SELECT * FROM dates WHERE id = '$ID' AND userid = ".Utility::GetLoggedInUser()->UserId." ");
    }

    /** @param DateModel $DateModel
     * @return mysqli_result */
    public function Insert($DateModel)
    {
        $this->db->Query("INSERT INTO dates
        (date,
        unittype,
        nthunit,
        impossibledayofmonth,
        usedayofweek,
        useendofmonth,
        userid)

        VALUES

        ('".$DateModel->Date."',
        '".$DateModel->UnitType."',
        '".$DateModel->NthUnit."',
        '".$DateModel->ImpossibleDayOfMonthBehavior."',
        '".$DateModel->UseDayOfWeek."',
        '".$DateModel->UseEndOfMonth."',
        ".Utility::GetLoggedInUser()->UserId."
        )");

        return $this->db->conn->insert_id;
    }

    /** @param DateModel $DateModel */
    public function Update($DateModel)
    {
        if (isset($DateModel) && !is_null($DateModel->DateId)) {
            $this->db->Query("UPDATE dates
            SET date = '" . $DateModel->Date . "',
            unittype = '" . $DateModel->UnitType . "',
            nthunit = '" . $DateModel->NthUnit . "',
            impossibledayofmonth = '" . $DateModel->ImpossibleDayOfMonthBehavior . "',
            usedayofweek = '" . $DateModel->UseDayOfWeek . "',
            useendofmonth = '" . $DateModel->UseEndOfMonth . "'
            WHERE
            id = '" . $DateModel->DateId . "'
            AND userid = ".Utility::GetLoggedInUser()->UserId."");
        }
    }

    public function Delete($DateID)
    {
        if(is_numeric($DateID)) {
            $this->db->Query("DELETE FROM dates
          WHERE id = '$DateID'
          AND userid = ".Utility::GetLoggedInUser()->UserId."");
        }
    }
}