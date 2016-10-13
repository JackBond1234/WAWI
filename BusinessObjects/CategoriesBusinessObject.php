<?php

class CategoriesBusinessObject{
    static function ResultToModelArray($result)
    {
        $return = array();
        $DateDataAccess = new DateDataAccess();

        while($row = mysqli_fetch_array($result))
        {
            $returnRow = new CategoriesModel();
            $returnRow->AccrualAmount = $row["accrualamount"];
            $returnRow->AccrueBy = $row["accrueby"];
            $returnRow->Balance = $row["balance"];
            $returnRow->CategoryId = intval($row["catid"]);
            $returnRow->HigherPriorityCategory = $row["higherpriority"]!=null ? intval($row["higherpriority"]) : null;
            $returnRow->LowerPriorityCategory = $row["lowerpriority"]!=null ? intval($row["lowerpriority"]) : null;
            $returnRow->Name = strval($row["name"]);
            $returnRow->Cap = floatval($row["cap"]);
            $returnRow->Color = strval($row["color"]);
            $returnRow->LastTarget = intval($row["lasttarget"]);
            $returnRow->AccruedInPeriod = floatval($row["accruedinperiod"]);
            if (!is_null($row["targetdate"])) {
                $returnRow->TargetDate = DateBusinessObject::FirstResultToModel($DateDataAccess->SelectById($row["targetdate"]));
            }
            else
            {
                $returnRow->TargetDate = null;
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
            $returnRow = new CategoriesModel();
            $returnRow->AccrualAmount = $row["accrualamount"];
            $returnRow->AccrueBy = $row["accrueby"];
            $returnRow->Balance = $row["balance"];
            $returnRow->CategoryId = intval($row["catid"]);
            $returnRow->HigherPriorityCategory = $row["higherpriority"] != null ? intval($row["higherpriority"]) : null;
            $returnRow->LowerPriorityCategory = $row["lowerpriority"] != null ? intval($row["lowerpriority"]) : null;
            $returnRow->Name = strval($row["name"]);
            $returnRow->Cap = $row["cap"];
            $returnRow->Color = strval($row["color"]);
            $returnRow->LastTarget = intval($row["lasttarget"]);
            $returnRow->AccruedInPeriod = floatval($row["accruedinperiod"]);
            if (!is_null($row["targetdate"])) {
                $returnRow->TargetDate = DateBusinessObject::FirstResultToModel($DateDataAccess->SelectById($row["targetdate"]));
            }
            else
            {
                $returnRow->TargetDate = null;
            }
        }

        return $returnRow;
    }

    static function AddNewCategory($Name)
    {
        $CategoriesDataAccess = new CategoriesDataAccess();
        /** @var CategoriesModel $LowestPriorityCategory */
        $LowestPriorityCategory = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectLowestPriority());
        if ($LowestPriorityCategory != null) {
            $LowestPriorityId = $LowestPriorityCategory->CategoryId;
        }
        else {
            $LowestPriorityId = null;
        }
        $NewCategory = new CategoriesModel();
        $NewCategory->Name = $Name;
        $NewCategory->HigherPriorityCategory = $LowestPriorityId;
        $NewCategory->CategoryId = $CategoriesDataAccess->Insert($NewCategory);
        if($LowestPriorityCategory != null) {
            $LowestPriorityCategory->LowerPriorityCategory = $NewCategory->CategoryId;
            $CategoriesDataAccess->Update($LowestPriorityCategory);
        }
    }

    static function DeleteCategory($CurrentID)
    {
        $CategoriesDataAccess = new CategoriesDataAccess();
        $CurrentCategory = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($CurrentID));
        $HigherCategory = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($CurrentCategory->HigherPriorityCategory));
        $LowerCategory = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($CurrentCategory->LowerPriorityCategory));
        if (isset($HigherCategory))
        {
            if (isset($LowerCategory)) {
                $HigherCategory->LowerPriorityCategory = $LowerCategory->CategoryId;
            }
            else
            {
                $HigherCategory->LowerPriorityCategory = NULL;
            }
            $CategoriesDataAccess->Update($HigherCategory);
        }
        if (isset($LowerCategory)) {
            if (isset($HigherCategory)) {
                $LowerCategory->HigherPriorityCategory = $HigherCategory->CategoryId;
            }
            else
            {
                $LowerCategory->HigherPriorityCategory = NULL;
            }
            $CategoriesDataAccess->Update($LowerCategory);
        }
        $CategoriesDataAccess->Delete($CurrentCategory->CategoryId);
    }

    /**
     * @param CategoriesModel[] $CategoryArray
     * @param int $SearchId
     * @return int
     */
    static function FindCategory($CategoryArray, $SearchId)
    {
        foreach($CategoryArray as $index => $Category)
        {
            if ($Category->CategoryId == $SearchId)
            {
                return $index;
            }
        }
        return -1;
    }
}