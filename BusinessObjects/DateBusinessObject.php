<?php

class DateBusinessObject{
    static function ResultToModelArray($result)
    {
        $return = array();
        while($row = mysqli_fetch_array($result))
        {
            $returnRow = new DateModel();
            $returnRow->DateId = intval($row["id"]);
            $returnRow->Date = intval($row["date"]);
            $returnRow->UnitType = intval($row["unittype"]);
            $returnRow->NthUnit = intval($row["nthunit"]);
            $returnRow->ImpossibleDayOfMonthBehavior = intval($row["impossibledayofmonth"]);
            $returnRow->UseDayOfWeek = boolval($row["usedayofweek"]);
            $returnRow->UseEndOfMonth = boolval($row["useendofmonth"]);
            $return[] = $returnRow;
        }
        return $return;
    }

    static function FirstResultToModel($result)
    {
        $returnRow = null;

        if($row = mysqli_fetch_array($result)) {
            $returnRow = new DateModel();
            $returnRow->DateId = intval($row["id"]);
            $returnRow->Date = intval($row["date"]);
            $returnRow->UnitType = intval($row["unittype"]);
            $returnRow->NthUnit = intval($row["nthunit"]);
            $returnRow->ImpossibleDayOfMonthBehavior = intval($row["impossibledayofmonth"]);
            $returnRow->UseDayOfWeek = boolval($row["usedayofweek"]);
            $returnRow->UseEndOfMonth = boolval($row["useendofmonth"]);
        }

        return $returnRow;
    }

    /** @param DateModel $DateModel
     * @param int $TargetDate
     * @return bool */
    static function IsDateOnThisDay($DateModel, $TargetDate)
    {
        $DayInSeconds = 86400;
        $DSTadjustment = (date("I", $TargetDate) - date("I", $DateModel->Date)) * 3600;
        if ($TargetDate < $DateModel->Date || !is_int($TargetDate))
        {
            return false;
        }

        //If a static date
        if ($DateModel->NthUnit == 0)
        {
            if ($DateModel->UseEndOfMonth) {
                if (Utility::EndOfMonth(Utility::BeginningOfDay($DateModel->Date)) == Utility::BeginningOfDay($TargetDate)) {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                if (Utility::BeginningOfDay($DateModel->Date) == Utility::BeginningOfDay($TargetDate)) {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        //Repeating by day
        if ($DateModel->UnitType == 0) {
            if ((Utility::BeginningOfDay($TargetDate) - Utility::BeginningOfDay($DateModel->Date) + $DSTadjustment) % ($DayInSeconds * $DateModel->NthUnit) == 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        //Repeating by week
        if ($DateModel->UnitType == 1){
            if ((Utility::BeginningOfDay($TargetDate) - Utility::BeginningOfDay($DateModel->Date) + $DSTadjustment) % ($DayInSeconds * 7 * $DateModel->NthUnit) == 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        //Repeating by month
        if ($DateModel->UnitType == 2){
            //By number of day in month
            if ($DateModel->UseDayOfWeek == false) {
                $OriginalDate = $DateModel->Date;//1-1
                if ($DateModel->UseEndOfMonth == true) {
                    $DateModel->ImpossibleDayOfMonthBehavior = 0;
                    $OriginalDate = Utility::BeginningOfMonth($DateModel->Date);
                }
                $LoopDay = $OriginalDate;
                $loopctr = $DateModel->NthUnit;
                do {
                    if ($DateModel->UseEndOfMonth == false && Utility::BeginningOfDay($LoopDay) == Utility::BeginningOfDay($TargetDate)) {
                        return true;
                    }
                    if ($DateModel->UseEndOfMonth == true && Utility::EndOfMonth(Utility::BeginningOfDay($LoopDay)) == Utility::BeginningOfDay($TargetDate)) {
                        return true;
                    }
                    do {
                        $LoopDay = Utility::SkipMonth($OriginalDate, $loopctr, $DateModel->ImpossibleDayOfMonthBehavior);
                        if ($LoopDay === false) {return false;}
                        $loopctr += $DateModel->NthUnit;
                    } while ($LoopDay === "Impossible");
                } while ($LoopDay <= Utility::BeginningOfDay($TargetDate));
                return false;
            }
            else
            {
                $dayofmonth = date("j", $DateModel->Date);
                $dayofweek = date("w",$DateModel->Date);
                $firstdayofweekinmonth = (($dayofmonth-1) % 7)+1;
                $firstplusnthweekday = (($dayofmonth-$firstdayofweekinmonth)/7);

                $dayoftargetmonth = date("j", $TargetDate);
                $dayoftargetweek = date("w", $TargetDate);
                $dayofweekdiff = $dayoftargetweek - $dayofweek;
                $workingdayofmonth = $dayoftargetmonth - $dayofweekdiff;
                $workingdayofmonth = (($workingdayofmonth-1) % 7)+1;
                $idealtargetmatch = $workingdayofmonth + ($firstplusnthweekday * 7);

                if ($DateModel->UseEndOfMonth == false) {
                    if (checkdate(date('m', $TargetDate), $idealtargetmatch, date('y', $TargetDate))) {
                        if ($dayoftargetmonth != $idealtargetmatch) {
                            return false;
                        }
                    } else {
                        if ($DateModel->ImpossibleDayOfMonthBehavior == 0) {
                            if (Utility::BeginningOfDay($TargetDate) != Utility::BeginningOfDay(Utility::EndOfMonth($TargetDate))) {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    }
                }
                else
                {
                    if (Utility::BeginningOfDay($TargetDate) != Utility::BeginningOfDay(Utility::EndOfMonth($TargetDate)))
                    {
                        return false;
                    }
                }

                $yearofdate = date('y', $DateModel->Date);
                $yearoftarget = date('y', $TargetDate);
                $yeardifferenceinmonths = ($yearoftarget - $yearofdate) * 12;

                $monthofdate = date("m", $DateModel->Date);
                $monthoftarget = date("m", $TargetDate) + $yeardifferenceinmonths;

                if (abs($monthoftarget - $monthofdate) % $DateModel->NthUnit == 0) {
                    return true;
                }

                return false;
            }
        }
        //Repeating by year
        if ($DateModel->UnitType == 3){
            $LoopDay = $DateModel->Date;
            $loopctr = $DateModel->NthUnit;
            do
            {
                if ($DateModel->UseEndOfMonth == false && Utility::BeginningOfDay($LoopDay) == Utility::BeginningOfDay($TargetDate))
                {
                    return true;
                }
                if ($DateModel->UseEndOfMonth == true && Utility::EndOfMonth(Utility::BeginningOfDay($LoopDay)) == Utility::BeginningOfDay($TargetDate))
                {
                    return true;
                }
                do {
                    $LoopDay = Utility::SkipYear($DateModel->Date, $loopctr, $DateModel->ImpossibleDayOfMonthBehavior);
                    if ($LoopDay === false) {return false;}
                    $loopctr += $DateModel->NthUnit;
                } while ($LoopDay === "Impossible");
            } while ($LoopDay <= Utility::BeginningOfDay($TargetDate));
        }

        return false;

    }

    /** @param DateModel $DateModel
     * @param int $startDate
     * @param int $endDate
     * @return bool */
    static function OccurrencesBetweenDates($DateModel, $startDate, $endDate)
    {
        if ($DateModel->UnitType == 3 && false)
        {
            //Not currently workable attempt to make yearly timespans more efficient
            $occurrences = 0;
            $loopdate = Utility::BeginningOfDay($startDate);
            $Y = date('Y', $loopdate);
            $m = date("m", $loopdate);
            $d = date("d", $loopdate);

            do {
                if (checkdate($m, $d, $Y + 1)) {
                    $occurrences++;
                }
                $Y++;
            } while(!checkdate($m, $d, $Y + 1) || strtotime($Y."-".$m."-".$d)<$endDate);
        }
        else {
            $loopdate = Utility::BeginningOfDay($startDate);
            $occurrences = 0;

            do {
                if (self::IsDateOnThisDay($DateModel, $loopdate)) {
                    $occurrences++;
                }
                $loopdate = Utility::SkipDay($loopdate, 1);
            } while ($loopdate <= Utility::BeginningOfDay($endDate));

            return $occurrences;
        }
    }

    /**
     * @param DateModel $DateModel
     * @param int $StartTime
     * @return int
     */
    static function GetNextOccurrence($DateModel, $StartTime = null)
    {
        if ($StartTime == null){
            $StartTime = time();
        }
        $LoopDate = Utility::BeginningOfDay($StartTime);

        while (Utility::SkipDay($LoopDate,1) !== false && !self::IsDateOnThisDay($DateModel, $LoopDate))
        {
            $LoopDate = Utility::SkipDay($LoopDate, 1);
        }

        return $LoopDate;
    }
}