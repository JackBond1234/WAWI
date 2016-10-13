<?php

class GlobalsDataAccess {
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    public function Select()
    {
        return $this->db->Query("SELECT * FROM globals WHERE userid = ".Utility::GetLoggedInUser()->UserId." ");
    }

    /** @param GlobalsModel $GlobalsModel */
    public function Update($GlobalsModel)
    {
        if (isset($GlobalsModel)) {
            $this->db->Query("UPDATE globals
            SET totalbalance = '" . $GlobalsModel->TotalBalance . "',
            expectedperiodincome = '" . $GlobalsModel->ExpectedPeriodIncome . "',
            payfreqdateid = '" . $GlobalsModel->Date->DateId . "'
            WHERE userid = ".Utility::GetLoggedInUser()->UserId." ");
        }
    }

    /** @param GlobalsModel $GlobalsModel */
    public function Insert($GlobalsModel) {
        if (isset($GlobalsModel))
        {
            $this->db->Query("INSERT INTO globals (
                totalbalance,
                expectedperiodincome,
                payfreqdateid,
                userid)
                VALUES (
                '".$GlobalsModel->TotalBalance."',
                '".$GlobalsModel->ExpectedPeriodIncome."',
                '".$GlobalsModel->Date->DateId."',
                ".Utility::GetLoggedInUser()->UserId.")"
            );
        }
    }
}