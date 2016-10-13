<?php

class RebalanceDataAccess {
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    public function Select()
    {
        return $this->db->Query("SELECT * FROM rebalance WHERE userid = ".Utility::GetLoggedInUser()->UserId." ORDER BY id");
    }

    public function SelectById($ID)
    {
        return $this->db->Query("SELECT * FROM rebalance WHERE id = '$ID' AND userid = ".Utility::GetLoggedInUser()->UserId." ");
    }

    public function SelectByCategoryAndType($CategoryId, $TypeId)
    {
        return $this->db->Query("SELECT * FROM rebalance WHERE catid = '$CategoryId' AND type = '$TypeId' AND userid = ".Utility::GetLoggedInUser()->UserId." ");
    }

    /** @param RebalanceModel $RebalanceModel
     * @return int */
    public function Insert($RebalanceModel)
    {
        $this->db->Query("INSERT INTO rebalance
        (catid,
        type,
        surplusordeficit,
        sendtopullfrom,
        dateid,
        userid)

        VALUES

        ('".$RebalanceModel->CategoryId."',
        '".$RebalanceModel->TriggerType."',
        '".$RebalanceModel->SurplusDeficit."',
        ".$RebalanceModel->SendToPullFrom.",
        '".$RebalanceModel->Date->DateId."',
        ".Utility::GetLoggedInUser()->UserId."
        )");

        return $this->db->conn->insert_id;
    }

    /** @param RebalanceModel $RebalanceModel */
    public function Update($RebalanceModel)
    {
        if (isset($RebalanceModel) && !is_null($RebalanceModel->RebalanceId)) {
            $this->db->Query("UPDATE rebalance
            SET catid = '" . $RebalanceModel->CategoryId . "',
            type = '" . $RebalanceModel->TriggerType . "',
            surplusordeficit = '" . $RebalanceModel->SurplusDeficit . "',
            sendtopullfrom = '" . $RebalanceModel->SendToPullFrom . "',
            dateid = '" . $RebalanceModel->Date->DateId . "'
            WHERE
            id = '" . $RebalanceModel->RebalanceId . "'
            AND userid = ".Utility::GetLoggedInUser()->UserId." ");
        }
    }

    public function Delete($RebalanceID)
    {
        if(is_numeric($RebalanceID)) {
            $this->db->Query("DELETE FROM rebalance
          WHERE id = '$RebalanceID'
          AND userid = ".Utility::GetLoggedInUser()->UserId." ");
        }
    }
}