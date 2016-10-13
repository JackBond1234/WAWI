<?php

class RebalanceBusinessObject
{
    static function ResultToModelArray($result)
    {
        $return = array();
        $DateDataAccess = new DateDataAccess();
        while($row = mysqli_fetch_array($result))
        {
            $returnRow = new RebalanceModel();
            $returnRow->RebalanceId = $row["id"];
            $returnRow->CategoryId = $row["catid"];
            $returnRow->TriggerType = $row["type"];
            $returnRow->SurplusDeficit = $row["surplusordeficit"];
            $returnRow->SendToPullFrom = $row["sendtopullfrom"];
            if (!is_null($row["dateid"])) {
                $returnRow->Date = DateBusinessObject::FirstResultToModel($DateDataAccess->SelectById($row["dateid"]));
            }
            else
            {
                $returnRow->Date = null;
            }
            $return[] = $returnRow;
        }
        return $return;
    }

    static function FirstResultToModel($result)
    {
        $returnRow = null;
        $DateDataAccess = new DateDataAccess();

        if($row = mysqli_fetch_array($result)) {
            $returnRow = new RebalanceModel();
            $returnRow->RebalanceId = $row["id"];
            $returnRow->CategoryId = $row["catid"];
            $returnRow->TriggerType = $row["type"];
            $returnRow->SurplusDeficit = $row["surplusordeficit"];
            $returnRow->SendToPullFrom = $row["sendtopullfrom"];
            if (!is_null($row["dateid"])) {
                $returnRow->Date = DateBusinessObject::FirstResultToModel($DateDataAccess->SelectById($row["dateid"]));
            }
            else
            {
                $returnRow->Date = null;
            }
        }

        return $returnRow;
    }

    /** @param RebalanceModel $RebalanceModel
     * @param DateModel $DateModel*/
    static function AddNewRebalance($RebalanceModel, $DateModel)
    {
        $RebalanceDataAccess = new RebalanceDataAccess();
        $DateDataAccess = new DateDataAccess();

        $RebalanceModel->Date = $DateModel;

        $RebalanceModel->Date->DateId = $DateDataAccess->Insert($DateModel);
        $RebalanceDataAccess->Insert($RebalanceModel);
    }

    /**
     * @param RebalanceModel $RebalanceModel
     */
    static function DeleteRebalance($RebalanceModel)
    {
        $RebalanceDataAccess = new RebalanceDataAccess();
        $DateDataAccess = new DateDataAccess();

        $DateDataAccess->Delete($RebalanceModel->Date->DateId);
        $RebalanceDataAccess->Delete($RebalanceModel->RebalanceId);
    }
}