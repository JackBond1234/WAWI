<?php

class GlobalsService {

    private $GlobalsDataAccess;
    private $CategoriesDataAccess;

    function __construct()
    {
        $this->GlobalsDataAccess = new GlobalsDataAccess();
        $this->CategoriesDataAccess = new CategoriesDataAccess();
    }

    /**
     * @param string $TargetDate
     * @param float $TargetAmount
     * @param float $Balance
     * @return ResponseModel
     */
    public function GetEstimatedAccrualRate($TargetDate, $TargetAmount, $Balance, $TodaysDate = null)
    {
        $Response = new ResponseModel();

        try
        {
            if ($TodaysDate == null){
                $TodaysDate = time();
            }
            /** @var GlobalsModel $Globals */
            $Globals = GlobalsBusinessObject::FirstResultToModel($this->GlobalsDataAccess->Select());

            $Occurrences = DateBusinessObject::OccurrencesBetweenDates($Globals->Date, $TodaysDate, $TargetDate);

            if ($Occurrences == 0) {$Occurrences = 1;}

            $result = ($TargetAmount - $Balance) / $Occurrences;

            if ($result < 0) {$result = 0;}
            $Response->Success = true;
            $Response->Data = $result;
            $Response->Message = "The estimation was calculated successfully";
        }
        catch (Exception $e)
        {
            $Response->Success = false;
            $Response->Message = $e->getMessage();
        }

        return $Response;
    }
}