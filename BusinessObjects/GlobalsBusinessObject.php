<?php

class GlobalsBusinessObject{
    static function FirstResultToModel($result)
    {
        $returnRow = null;
        $DateDataAccess = new DateDataAccess();

        if($row = mysqli_fetch_array($result)) {
            $returnRow = new GlobalsModel();
            $returnRow->TotalBalance = floatval($row["totalbalance"]);
            $returnRow->ExpectedPeriodIncome = floatval($row["expectedperiodincome"]);
            if (!is_null($row["payfreqdateid"])) {
                $returnRow->Date = DateBusinessObject::FirstResultToModel($DateDataAccess->SelectById($row["payfreqdateid"]));
            }
            else
            {
                $returnRow->Date = null;
            }
        }

        return $returnRow;
    }

    static function addOrRemoveFunds($amount)
    {
        $GlobalsDataAccess = new GlobalsDataAccess();
        $updateModel = GlobalsBusinessObject::FirstResultToModel($GlobalsDataAccess->Select());
        $updateModel->TotalBalance += floatval($amount);
        $GlobalsDataAccess->Update($updateModel);
    }

    /** @param GlobalsModel $GlobalsModel */
    static function InsertOrUpdate($GlobalsModel)
    {
        $DateDataAccess = new DateDataAccess();
        if ($GlobalsModel->Date->DateId == null) {
            $GlobalsModel->Date->DateId = $DateDataAccess->Insert($GlobalsModel->Date);
        }
        else
        {
            $DateDataAccess->Update($GlobalsModel->Date);
        }
        $GlobalsDataAccess = new GlobalsDataAccess();
        if (mysqli_num_rows($GlobalsDataAccess->Select()) > 0)
        {
            $GlobalsDataAccess->Update($GlobalsModel);
        }
        else
        {
            $GlobalsDataAccess->Insert($GlobalsModel);
        }
    }
}