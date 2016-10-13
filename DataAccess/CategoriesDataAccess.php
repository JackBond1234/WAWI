<?php

class CategoriesDataAccess {
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    public function Select()
    {
        return $this->db->Query("SELECT * FROM categories WHERE userid = ".Utility::GetLoggedInUser()->UserId." AND isdeleted = false ORDER BY higherpriority");
    }

    public function SelectLowestPriority()
    {
        return $this->db->Query("SELECT * FROM categories WHERE lowerpriority IS NULL AND isdeleted = false AND userid = ".Utility::GetLoggedInUser()->UserId." ");
    }

    public function SelectHighestPriority()
    {
        return $this->db->Query("SELECT * FROM categories WHERE higherpriority IS NULL AND isdeleted = false AND userid = ".Utility::GetLoggedInUser()->UserId." ");
    }

    public function SelectById($ID)
    {
        return $this->db->Query("SELECT * FROM categories WHERE catid = '$ID' AND userid = ".Utility::GetLoggedInUser()->UserId." ");
    }

    /** @param CategoriesModel $CategoriesModel */
    public function Insert($CategoriesModel)
    {
        $this->db->Query("INSERT INTO categories
        (balance,
        higherpriority,
        lowerpriority,
        accrueby,
        accrualamount,
        name,
        cap,
        color,
        targetdate,
        userid,
        lasttarget,
        accruedinperiod)

        VALUES

        ('".$CategoriesModel->Balance."',
        ".Utility::valOrNull($CategoriesModel->HigherPriorityCategory).",
        ".Utility::valOrNull($CategoriesModel->LowerPriorityCategory).",
        '".$CategoriesModel->AccrueBy."',
        '".$CategoriesModel->AccrualAmount."',
        '".$CategoriesModel->Name."',
        '".$CategoriesModel->Cap."',
        '".$CategoriesModel->Color."',
        ".(isset($CategoriesModel->TargetDate) ? "'{$CategoriesModel->TargetDate->DateId}'" : "null").",
        ".Utility::GetLoggedInUser()->UserId.",
        '".$CategoriesModel->LastTarget."',
        '".$CategoriesModel->AccruedInPeriod."'
        )");

        return $this->db->conn->insert_id;
    }

    /** @param CategoriesModel $CategoriesModel */
    public function Update($CategoriesModel)
    {
        if (isset($CategoriesModel) && !is_null($CategoriesModel->CategoryId)) {
            $this->db->Query("UPDATE categories
            SET balance = '" . $CategoriesModel->Balance . "',
            higherpriority = " . Utility::valOrNull($CategoriesModel->HigherPriorityCategory) . ",
            lowerpriority = " . Utility::valOrNull($CategoriesModel->LowerPriorityCategory) . ",
            accrueby = '" . $CategoriesModel->AccrueBy . "',
            accrualamount = '" . $CategoriesModel->AccrualAmount . "',
            name = '" . $CategoriesModel->Name . "',
            cap = '" . $CategoriesModel->Cap . "',
            color = '". $CategoriesModel->Color . "',
            targetdate = " . (isset($CategoriesModel->TargetDate) ? "'{$CategoriesModel->TargetDate->DateId}'" : "null") . ",
            lasttarget = '".$CategoriesModel->LastTarget."',
            accruedinperiod = '".$CategoriesModel->AccruedInPeriod."'
            WHERE
            catid = '" . $CategoriesModel->CategoryId . "'
            AND userid = ".Utility::GetLoggedInUser()->UserId." ");
        }
    }

    public function Delete($CategoryID)
    {
        if(is_numeric($CategoryID)) {
            $this->db->Query("UPDATE categories
            SET isdeleted = true
          WHERE catid = '$CategoryID'
          AND userid = ".Utility::GetLoggedInUser()->UserId." ");
        }
    }
}