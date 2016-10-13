<?php

class CategoriesService {

    private $GlobalsDataAccess;
    private $CategoriesDataAccess;
    private $RebalanceDataAccess;

    function __construct()
    {
        $this->GlobalsDataAccess = new GlobalsDataAccess();
        $this->CategoriesDataAccess = new CategoriesDataAccess();
        $this->RebalanceDataAccess = new RebalanceDataAccess();
    }

    /**
     * @param CategoriesModel $Category
     * @param int $EventType
     * @return ResponseModel
     */
    public function RebalanceCategory($Category, $EventType)
    {
        $Category = clone $Category;
        $Response = new ResponseModel();

        try
        {
            /** @var CategoriesModel[] $Result */
            $Result = array($Category->CategoryId => $Category);
            /** @var RebalanceModel[] $Rebalances */
            $Rebalances = RebalanceBusinessObject::ResultToModelArray($this->RebalanceDataAccess->SelectByCategoryAndType($Category->CategoryId, $EventType));
            foreach($Rebalances as $Rebalance)
            {
                if (isset($Rebalance->SendToPullFrom)) {
                    //Retrieve the foreign category
                    if (!isset($Result[$Rebalance->SendToPullFrom])) {
                        $Result[$Rebalance->SendToPullFrom] = clone CategoriesBusinessObject::FirstResultToModel($this->CategoriesDataAccess->SelectById($Rebalance->SendToPullFrom));
                    }

                    $ForeignCategory = $Result[$Rebalance->SendToPullFrom];


                    //Send Surplus Away
                    if ($Category->Balance > 0 && in_array($Rebalance->SurplusDeficit, array(RebalanceModel::SURPLUS, RebalanceModel::SURPLUS_OR_DEFICIT))) {
                        //Foreign category reaches cap
                        if (abs($ForeignCategory->Cap) >= 0.005 && $ForeignCategory->Balance + $Category->Balance > $ForeignCategory->Cap) {
                            $Category->Balance -= ($ForeignCategory->Cap - $ForeignCategory->Balance);
                            $ForeignCategory->Balance = $ForeignCategory->Cap;
                        } //Foreign category receives full balance
                        else {
                            $ForeignCategory->Balance += $Category->Balance;
                            $Category->Balance = 0;
                        }
                    } //Even Out Deficit
                    else if ($Category->Balance < 0 && in_array($Rebalance->SurplusDeficit, array(RebalanceModel::DEFICIT, RebalanceModel::SURPLUS_OR_DEFICIT))) {
                        //Foreign category runs out
                        if ($ForeignCategory->Balance + $Category->Balance < 0) {
                            $Category->Balance += $ForeignCategory->Balance;
                            $ForeignCategory->Balance = 0;
                        } //Foreign category fulfills the deficit
                        else {
                            $ForeignCategory->Balance += $Category->Balance;
                            $Category->Balance = 0;
                        }
                    }
                }
                else
                {
                    if (($Category->Balance > 0 && $Rebalance->SurplusDeficit == RebalanceModel::SURPLUS) || ($Category->Balance < 0 && $Rebalance->SurplusDeficit == RebalanceModel::DEFICIT) || $Rebalance->SurplusDeficit == RebalanceModel::SURPLUS_OR_DEFICIT) {
                        $Category->Balance = 0;
                    }
                }
            }
            $Response->Success = true;
            $Response->Data = $Result;
            $Response->Message = "The rebalance completed successfully";
        }
        catch (Exception $e)
        {
            $Response->Success = false;
            $Response->Message = $e->getMessage();
        }

        return $Response;
    }

    public function GetUnallocated()
    {
        $Response = new ResponseModel();

        try {
            $Globals = GlobalsBusinessObject::FirstResultToModel($this->GlobalsDataAccess->Select());
            $Balance = $Globals->TotalBalance;
            $Pending = $Balance;
            $Categories = CategoriesBusinessObject::ResultToModelArray($this->CategoriesDataAccess->Select());

            if (count($Categories) > 0) {
                foreach ($Categories as $Category) {
                    $Pending -= $Category->Balance;
                }
            }
            $Response->Success = true;
            $Response->Message = "Unallocated amount was calculated successfully";
            $Response->Data = $Pending;
        }
        catch (Exception $e)
        {
            $Response->Success = false;
            $Response->Message = $e->getMessage();
        }

        return $Response;
    }

}