<?php
header('Content-Type: application/json');
include("includes.php");

if(count($_POST) > 0 && isset($_POST["method"]))
{
    if ($_POST["method"] == "refresh_index")
    {
        $GlobalsService = new GlobalsService();
        $CategoriesDataAccess = new CategoriesDataAccess();
        /** @var CategoriesModel[] $categoriesResultArray */
        $categoriesResultArray = CategoriesBusinessObject::ResultToModelArray($CategoriesDataAccess->Select());
        $GlobalsDataAccess = new GlobalsDataAccess();
        /** @var GlobalsModel $globals */
        $globals = GlobalsBusinessObject::FirstResultToModel($GlobalsDataAccess->Select());
        if (!is_null($globals)) {
            $pendingPool = $globals->TotalBalance;
            /** @var CategoriesModel[] $categories */
            $categories = array();
            $autoestimates = array();
            $firstCategory = null;
            if (count($categoriesResultArray) > 0) {
                foreach ($categoriesResultArray as $catResult) {
                    $categories[$catResult->CategoryId] = $catResult->toArray();
                        $pendingPool = $pendingPool - $catResult->Balance;
                }

                $firstCategory = array_values($categories)[0]["CategoryId"];
            }

            $output = array(
                "success" => true,
                "categories" => $categories,
                "firstcategory" => $firstCategory,
                "globals" => $globals->toArray(),
                "pendingPool" => $pendingPool
            );
        }
        else
        {
            $output = array(
                "success" => false
            );
        }

        echo json_encode($output);
    }
    else if ($_POST["method"] == "refresh_rebalance")
    {
        $CategoriesDataAccess = new CategoriesDataAccess();
        $RebalanceDataAccess = new RebalanceDataAccess();
        /** @var CategoriesModel[] $categoriesResultArray */
        $categoriesResultArray = CategoriesBusinessObject::ResultToModelArray($CategoriesDataAccess->Select());
        /** @var RebalanceModel[] $rebalanceResultArray */
        $rebalanceResultArray = ReBalanceBusinessObject::ResultToModelArray($RebalanceDataAccess->Select());

        $categories = array();
        /** @var CategoriesModel $categoriesResult */
        foreach($categoriesResultArray as $categoriesResult)
        {
            $categories[$categoriesResult->CategoryId] = $categoriesResult->toArray();
        }
        $rebalances = array();
        foreach($rebalanceResultArray as $rebalanceResult)
        {
            $rebalanceResultArray["RebalanceId"] = $rebalanceResult->RebalanceId;
            $rebalanceResultArray["CategoryId"] = $rebalanceResult->CategoryId;
            $rebalanceResultArray["CategoryName"] = $categories[$rebalanceResult->CategoryId]["Name"];
            $rebalanceResultArray["SendToPullFrom"] = $rebalanceResult->SendToPullFrom;
            $rebalanceResultArray["SendToPullFromName"] = (isset($categories[$rebalanceResult->SendToPullFrom]["Name"])?$categories[$rebalanceResult->SendToPullFrom]["Name"]:"Unallocated");
            $rebalanceResultArray["TriggerType"] = $rebalanceResult->TriggerType;
            $rebalanceResultArray["SurplusDeficit"] = $rebalanceResult->SurplusDeficit;
            if (!is_null($rebalanceResult->Date)) {
                $rebalanceResultArray["Today"] = DateBusinessObject::IsDateOnThisDay($rebalanceResult->Date, time());
            }
            else
            {
                $rebalanceResultArray["Today"] = false;
            }
            $rebalances[] = $rebalanceResultArray;
        }
        $categories = array_values($categories);

        $output = array(
            "categories" => $categories,
            "rebalances" => $rebalances
        );

        echo json_encode($output);
    }
    else if ($_POST["method"] == "create_category")
    {
        CategoriesBusinessObject::AddNewCategory($_POST["newcategoryname"]);
        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "arrange_categories")
    {
        $CategoriesDataAccess = new CategoriesDataAccess();
        foreach($_POST["categoryChanges"] as $categoryChange)
        {
            $updateModel = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($categoryChange["currid"]));
            $updateModel->HigherPriorityCategory = $categoryChange["upperid"];
            $updateModel->LowerPriorityCategory = $categoryChange["lowerid"];
            $CategoriesDataAccess->Update($updateModel);
        }
        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "delete_category")
    {
        CategoriesBusinessObject::DeleteCategory($_POST["deletecat"]);
        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "get_category")
    {
        $CategoriesDataAccess = new CategoriesDataAccess();
        $returnModel = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($_POST["catid"]));
        $returnArray = $returnModel->toArray();
        $returnArray["Success"] = true;
        echo json_encode($returnArray);
    }
    else if ($_POST["method"] == "update_category")
    {
        try {
            $CategoriesDataAccess = new CategoriesDataAccess();
            $DateDataAccess = new DateDataAccess();
            $updateModel = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($_POST["CategoryId"]));
            $updateModel->AccrueBy = intval($_POST["AccrueBy"]);
            $updateModel->AccrualAmount = floatval($_POST["AccrualAmount"]);
            $updateModel->Cap = floatval($_POST["Cap"]);

            $updateDate = true;
            if (is_null($updateModel->TargetDate)) {
                $updateModel->TargetDate = new DateModel();
                $updateDate = false;
            }
            else
            {
                if ($updateModel->LastTarget != DateBusinessObject::GetNextOccurrence($updateModel->TargetDate, strtotime($_POST["date"]))) {
                    $updateModel->AccruedInPeriod = 0.00;
                }
            }
            $updateModel->TargetDate->Date = strtotime($_POST["TargetDate"]);
            $updateModel->TargetDate->UnitType = $_POST["unittype"];
            if ($_POST["whentoperform"] == 0) {
                $updateModel->TargetDate->NthUnit = 0;
            } else {
                $updateModel->TargetDate->NthUnit = $_POST["nthunit"];
            }
            $updateModel->TargetDate->UseDayOfWeek = ($_POST["usedayofweekorlastday"] < 2 ? $_POST["usedayofweekorlastday"] : 0);
            $updateModel->TargetDate->UseEndOfMonth = ($_POST["usedayofweekorlastday"] == 3 ? 1 : 0);
            $updateModel->TargetDate->ImpossibleDayOfMonthBehavior = $_POST["impossibledate"];

            $updateModel->LastTarget = DateBusinessObject::GetNextOccurrence($updateModel->TargetDate, strtotime($_POST["date"]));

            if (Utility::ValidateColor($_POST["Color"])) {
                $updateModel->Color = $_POST["Color"];
            } else {
                $updateModel->Color = "#FFFFFF";
            }

            if ($updateDate == true) {
                $DateDataAccess->Update($updateModel->TargetDate);
            }
            else
            {
                $updateModel->TargetDate->DateId = $DateDataAccess->Insert($updateModel->TargetDate);
            }
            $CategoriesDataAccess->Update($updateModel);

            echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
        }
        catch (Exception $e)
        {
            echo json_encode(array("Success" => false, "Message" => $e));
        }
    }
    else if ($_POST["method"] == "add_funds")
    {
        GlobalsBusinessObject::addOrRemoveFunds($_POST["amount"]);
        $GlobalsDataAccess = new GlobalsDataAccess();
        $updatedGlobal = GlobalsBusinessObject::FirstResultToModel($GlobalsDataAccess->Select());

        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "spend")
    {
        $result = array("TotalBalance" => 0);
        if (is_numeric($_POST["amount"]) && $_POST["amount"] > 0) {
            $CategoriesService = new CategoriesService();
            $GlobalsDataAccess = new GlobalsDataAccess();
            $CategoriesDataAccess = new CategoriesDataAccess();
            $Globals = GlobalsBusinessObject::FirstResultToModel($GlobalsDataAccess->Select());
            $result = array("TotalBalance" => $Globals->TotalBalance - $_POST["amount"]);
            if ($_POST["amount"] <= $Globals->TotalBalance) {
                /** @var CategoriesModel $Categories */
                $Category = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($_POST["catid"]));
                $Category->Balance -= $_POST["amount"];
                $Globals->TotalBalance -= $_POST["amount"];
                $GlobalsDataAccess->Update($Globals);
                $CategoriesDataAccess->Update($Category);

                /** @var ResponseModel $RebalancedCategories */
                $RebalancedCategories = $CategoriesService->RebalanceCategory($Category, RebalanceModel::TRIGGER_TYPE_SPEND);

                if (is_array($RebalancedCategories->Data)) {
                    foreach ($RebalancedCategories->Data as $RebalancedCategory) {
                        $CategoriesDataAccess->Update($RebalancedCategory);
                    }
                }
            }
        }
        echo json_encode($result);
    }
    else if ($_POST["method"] == "category_send")
    {
        if ((is_numeric($_POST["fromcatid"]) || $_POST["fromcatid"] === "NULL") && (is_numeric($_POST["tocatid"]) || $_POST["tocatid"] === "NULL") && is_numeric($_POST["amount"]) && $_POST["amount"] > 0)
        {
            $CategoriesService = new CategoriesService();
            $PendingResult = $CategoriesService->GetUnallocated();
            if ($PendingResult->Success == true)
            {
                $Pending = $PendingResult->Data;
            }
            else
            {
                $Pending = 0;
            }
            $Amount = $_POST["amount"];
            $CategoriesDataAccess = new CategoriesDataAccess();
            if ($_POST["fromcatid"] !== "NULL") {
                $FromCategory = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($_POST["fromcatid"]));
                if ($FromCategory->Balance - $Amount >= 0) {
                    $FromCategory->Balance -= $_POST["amount"];
                }
                else
                {
                    $Amount = $FromCategory->Balance;
                    $FromCategory->Balance = 0;
                }
                $CategoriesDataAccess->Update($FromCategory);
            }
            else if ($Pending - $Amount < 0)
            {
                $Amount = $Pending;
            }
            if ($_POST["tocatid"] !== "NULL") {
                $ToCategory = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($_POST["tocatid"]));
                $ToCategory->Balance += $Amount;
                $CategoriesDataAccess->Update($ToCategory);
            }
        }
        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "add_rebalance")
    {
        if ($_POST["catid"] != $_POST["sendtopullfrom"]) {
            $RebalanceModel = new RebalanceModel();
            $RebalanceDateModel = new DateModel();
            $RebalanceModel->CategoryId = $_POST["catid"];
            $RebalanceModel->SurplusDeficit = $_POST["surplusordeficit"];
            $RebalanceModel->SendToPullFrom = $_POST["sendtopullfrom"];
            $RebalanceModel->TriggerType = $_POST["whentoperform"];
            $RebalanceDateModel->Date = strtotime($_POST["date"]);
            $RebalanceDateModel->UnitType = $_POST["unittype"];
            $RebalanceDateModel->NthUnit = $_POST["nthunit"];
            if ($_POST["usedayofweekorlastday"] == 1) {
                $RebalanceDateModel->UseDayOfWeek = true;
            } else if ($_POST["usedayofweekorlastday"] == 2) {
                $RebalanceDateModel->UseEndOfMonth = true;
            }
            $RebalanceDateModel->ImpossibleDayOfMonthBehavior = $_POST["impossibledate"];

            RebalanceBusinessObject::AddNewRebalance($RebalanceModel, $RebalanceDateModel);
        }
        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "delete_rebalance")
    {
        if (is_numeric($_POST["rebalanceid"]))
        {
            $RebalanceDataAccess = new RebalanceDataAccess();
            $RebalanceModel = RebalanceBusinessObject::FirstResultToModel($RebalanceDataAccess->SelectById($_POST["rebalanceid"]));
            RebalanceBusinessObject::DeleteRebalance($RebalanceModel);
        }
        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "auto_estimate")
    {
        if (is_numeric($_POST["auto_target"]) &&
            strtotime($_POST["auto_date"]) != false &&
            date("m/d/Y", strtotime($_POST["auto_date"])) == $_POST["auto_date"] &&
            is_numeric($_POST["whentoperform"]) &&
            ($_POST["whentoperform"] == 0 || (
                    is_numeric($_POST["unittype"]) &&
                    is_numeric($_POST["nthunit"]) &&
                    ($_POST["unittype"] < 2 || (
                            is_numeric($_POST["impossibledate"]) &&
                            ($_POST["unittype"] == 4 || (
                                    is_numeric($_POST["usedayofweekorlastday"])
                                )
                            )
                        )
                    )
                )
            )
        ) {
            $DateModel = new DateModel();
            $DateModel->Date = strtotime($_POST["auto_date"]);
            $DateModel->UnitType = $_POST["unittype"];
            if ($_POST["whentoperform"] == 0) {
                $DateModel->NthUnit = 0;
            }
            else {
                $DateModel->NthUnit = $_POST["nthunit"];
            }
            $DateModel->ImpossibleDayOfMonthBehavior = $_POST["impossibledate"];
            $DateModel->UseDayOfWeek = ($_POST["usedayofweekorlastday"] < 2 ? $_POST["usedayofweekorlastday"] : 0);
            $DateModel->UseEndOfMonth = ($_POST["usedayofweekorlastday"] == 2 ? 1 : 0);
            $NextDate = DateBusinessObject::GetNextOccurrence($DateModel, strtotime($_POST["date"]));

            $CategoriesDataAccess = new CategoriesDataAccess();
            $Category = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($_POST["catid"]));

            $GlobalsService = new GlobalsService();
            $EstimateResult = $GlobalsService->GetEstimatedAccrualRate($NextDate, $_POST["auto_target"], $Category->AccruedInPeriod, strtotime($_POST["date"]));

            if ($EstimateResult->Success == true) {
                $result = array("estimate"=>$EstimateResult->Data);
                echo json_encode($result);
            }
        }
        else
        {
            $result = array("estimate" => -1);
            echo json_encode($result);
        }
    }
    else if ($_POST["method"] == "current_auto_estimate")
    {
        if (is_numeric($_POST["catid"])) {

            $CategoriesDataAccess = new CategoriesDataAccess();
            $Category = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($_POST["catid"]));

            $NextDate = DateBusinessObject::GetNextOccurrence($Category->TargetDate, strtotime($_POST["date"]));

            $GlobalsService = new GlobalsService();
            $EstimateResult = $GlobalsService->GetEstimatedAccrualRate($NextDate, $Category->AccrualAmount, $Category->AccruedInPeriod, strtotime($_POST["date"]));

            if ($EstimateResult->Success == true) {
                $result = array("estimate"=>$EstimateResult->Data, "nextdate"=>$NextDate, "catid"=>$_POST["catid"]);
                echo json_encode($result);
            }
        }
        else
        {
            $result = array("estimate"=>-1, "nextdate"=>-1, "catid"=>-1);
            echo json_encode($result);
        }
    }
    else if ($_POST["method"] == "cascade_commit")
    {
        $CategoriesDataAccess = new CategoriesDataAccess();
        $CategoriesService = new CategoriesService();

        /** @var CategoriesModel[] $Categories */
        $Categories = CategoriesBusinessObject::ResultToModelArray($CategoriesDataAccess->Select());
        if (count($Categories) > 0) {
            foreach ($Categories as $index => $Category) {
                /** @var ResponseModel $RebalancedCategories */
                $RebalancedCategories = $CategoriesService->RebalanceCategory($Categories[$index], RebalanceModel::TRIGGER_TYPE_DISTRIBUTE);
                if (is_array($RebalancedCategories->Data)) {
                    /** @var CategoriesModel $RebalancedCategory */
                    foreach ($RebalancedCategories->Data as $RebalancedCategory) {
                        $Categories[CategoriesBusinessObject::FindCategory($Categories, $RebalancedCategory->CategoryId)] = $RebalancedCategory;
                        $CategoriesDataAccess->Update($RebalancedCategory);
                    }
                }
            }
        }

        foreach($_POST["newBalances"] as $newBalance)
        {
            $Cat = CategoriesBusinessObject::FirstResultToModel($CategoriesDataAccess->SelectById($newBalance["CategoryId"]));
            if ($Cat->LastTarget != $newBalance["TargetDate"]) { $Cat->AccruedInPeriod = 0.00; }
            $Cat->AccruedInPeriod += ($newBalance["AfterBalance"] - $Cat->Balance);
            if ($Cat->AccruedInPeriod < 0) { $Cat->AccruedInPeriod = 0.00; }
            $Cat->LastTarget = $newBalance["TargetDate"];
            $Cat->Balance = $newBalance["AfterBalance"];
            $CategoriesDataAccess->Update($Cat);
        }
        echo json_encode(array("Success" => true, "Message" => "Successful Execution"));
    }
    else if ($_POST["method"] == "cascade")
    {
        $GlobalsDataAccess = new GlobalsDataAccess();
        $CategoriesDataAccess = new CategoriesDataAccess();
        $CategoriesService = new CategoriesService();
        $GlobalsService = new GlobalsService();

        $newReBalance = array();

        /** @var CategoriesModel[] $Categories */
        $Categories = CategoriesBusinessObject::ResultToModelArray($CategoriesDataAccess->Select());
        if (count($Categories) > 0) {
            foreach ($Categories as $index => $Category) {
                /** @var ResponseModel $RebalancedCategories */
                $RebalancedCategories = $CategoriesService->RebalanceCategory($Categories[$index], RebalanceModel::TRIGGER_TYPE_DISTRIBUTE);
                if (is_array($RebalancedCategories->Data)) {
                    /** @var CategoriesModel $RebalancedCategory */
                    foreach ($RebalancedCategories->Data as $RebalancedCategory) {
                        if($Categories[CategoriesBusinessObject::FindCategory($Categories, $RebalancedCategory->CategoryId)]->Balance != $RebalancedCategory->Balance) {
                            $newReBalance[] = array("CatId" => $RebalancedCategory->CategoryId, "Before" => $Categories[CategoriesBusinessObject::FindCategory($Categories, $RebalancedCategory->CategoryId)]->Balance, "After" => $RebalancedCategory->Balance);
                        }
                        $Categories[CategoriesBusinessObject::FindCategory($Categories, $RebalancedCategory->CategoryId)] = $RebalancedCategory;
                    }
                }
            }
        }

        $Globals = GlobalsBusinessObject::FirstResultToModel($GlobalsDataAccess->Select());
        if (isset($_POST["UseProjectedPaycheck"])) {
            $StartingPool = $Pool = $Balance = $Globals->ExpectedPeriodIncome;
        } else {
            $StartingPool = $Pool = $Balance = $Globals->TotalBalance;
        }
        /** @var CategoriesModel[] $Categories */
//        $Categories = CategoriesBusinessObject::ResultToModelArray($CategoriesDataAccess->Select());
        if (count($Categories) > 0) {
            $orderedCategories = array();
            foreach ($Categories as $Category) {
                $orderedCategories[$Category->CategoryId] = $Category;
                if (!isset($_POST["UseProjectedPaycheck"])) {
                    $Pool -= $Category->Balance;
                    $StartingPool -= $Category->Balance;
                }
            }

            $curcat = $Categories[0];
            $exitLoop = false;
            $groupPoolStart = null;
            $remainder = 0;
            do {
                $desiredPullFromPool = 0;
                $AccrualAmount = 0;
                if ($curcat->AccrueBy != 2) {$groupPoolStart = null;}
                switch($curcat->AccrueBy) {
                    case 0:
                        $desiredPullFromPool = $curcat->AccrualAmount;
                        break;
                    case 1:
                        $desiredPullFromPool = $Pool*$curcat->AccrualAmount/100;
                        break;
                    case 2:
                        if (is_null($groupPoolStart)) {$groupPoolStart = $Pool;}
                        $desiredPullFromPool = $groupPoolStart*$curcat->AccrualAmount/100;
                        break;
                    case 3:
                        $NextDate = DateBusinessObject::GetNextOccurrence($curcat->TargetDate, strtotime($_POST["date"]));
                        if ($curcat->LastTarget != $NextDate) { $curcat->AccruedInPeriod = 0.00; }
                        $EstimateResult = $GlobalsService->GetEstimatedAccrualRate($NextDate, $curcat->AccrualAmount, $curcat->AccruedInPeriod, strtotime($_POST["date"]));
                        if ($EstimateResult->Success == true)
                        {
                            $AccrualAmount = floor($EstimateResult->Data*100)/100;
                        }
                        $desiredPullFromPool = $AccrualAmount;
                        break;
                    default:
                        break;
                }
                if($Pool-$desiredPullFromPool < 0) {$desiredPullFromPool = $Pool;}
                if($desiredPullFromPool < 0) {$desiredPullFromPool = 0;}
                if (abs($curcat->Cap) >= 0.005 && $desiredPullFromPool+$curcat->Balance > $curcat->Cap)
                {
                    $desiredPullFromPool = ($curcat->Cap - $curcat->Balance > 0 ? $curcat->Cap - $curcat->Balance : 0);
                }
                if ($remainder >= 0.01 && $desiredPullFromPool > 0 && $curcat->AccrueBy != 0) {
                    $desiredPullFromPool += 0.01;
                    $remainder -= 0.01;
                }
                $remainder += $desiredPullFromPool - Utility::floor($desiredPullFromPool, 2);//(floor($desiredPullFromPool*100)/100);
                if ($curcat->LowerPriorityCategory != null) {
                    $desiredPullFromPool = Utility::floor($desiredPullFromPool, 2);//floor($desiredPullFromPool * 100) / 100;
                }
                else
                {
                    $desiredPullFromPool = ceil($desiredPullFromPool * 100) / 100;
                }
                if ($desiredPullFromPool < 0){
                    $desiredPullFromPool = 0;
                }
                $preBalance = $curcat->Balance;
                $curcat->Balance += $desiredPullFromPool;
                $Pool -= $desiredPullFromPool;
                $Pool = round($Pool*100)/100;
                $returndate = isset($NextDate) ? $NextDate : time();
                $newBalanceArray[] = array("Name" => $curcat->Name,
                                          "BeforeBalance" => $preBalance,
                                          "AccrueBy" => $curcat->AccrueBy,
                                          "AccrualAmount" => $curcat->AccrualAmount,
                                          "TargetDate" => $returndate,
                                          "EstimatedAccrualAmount" => $AccrualAmount,
                                          "EffectiveAccrualAmount" => $desiredPullFromPool,
                                          "PoolAtPoint" => $Pool,
                                          "AfterBalance" => $curcat->Balance,
                                          "CategoryId" => $curcat->CategoryId);
                if ($curcat->LowerPriorityCategory != null) {
                    $curcat = $orderedCategories[$curcat->LowerPriorityCategory];
                }
                else
                {$exitLoop = true;}
            } while ($exitLoop != true);
        }
        echo json_encode(array("Success" => true, "Message" => "Successful Execution", "StartingPool" => $StartingPool, "NewReBalanceArray"=>$newReBalance, "NewBalanceArray"=>$newBalanceArray));
    }
}