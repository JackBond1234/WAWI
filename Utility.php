<?php

class Utility{

    static function valOrNull($val, $lit = false)
    {
        if (!isset($val) || is_null($val))
        {
            if ($lit) {
                return null;
            }
            else {
                return "null";
            }
        }
        else
        {
            return $val;
        }
    }

    static function BeginningOfDay($Date)
    {
        return strtotime("midnight",$Date);
    }

    static function EndOfMonth($Date)
    {
        $Date = self::BeginningOfDay($Date);
        $diff = date("t", $Date) - date("j", $Date);
        $Date = strtotime("+$diff days", $Date);

        return $Date;
    }

    static function BeginningOfMonth($Date)
    {
        $Date = self::BeginningOfDay($Date);
        $diff = date("t", $Date);
        $Date = strtotime("-$diff days +1 day", $Date);
        return $Date;
    }

    static function SkipMonth($Date, $HowMany, $ImpossibleDayOfMonth)
    {
        $Date = self::BeginningOfDay($Date);
        if (($HowMany > 0 && strtotime("+$HowMany months", $Date) > $Date) || ($HowMany < 0 && strtotime("+$HowMany months", $Date) < $Date)) {
            $MonthLater = strtotime("+$HowMany months", $Date);
            if (date("j", $MonthLater) != date("j", $Date)) {
                if ($ImpossibleDayOfMonth == 0) {
                    if (($HowMany > 0 && strtotime("+$HowMany months -" . date("j", $MonthLater) . " days", $Date) > $Date) || ($HowMany < 0 && strtotime("+$HowMany months -" . date("j", $MonthLater) . " days", $Date) < $Date)) {
                        return self::EndOfMonth(strtotime("+$HowMany months -" . date("j", $MonthLater) . " days", $Date));
                    }
                    else
                    {
                        return false;
                    }
                } else {
                    return "Impossible";
                }
            }
            return $MonthLater;
        }
        else
        {
            return false;
        }
    }

    static function SkipYear($Date, $HowMany, $ImpossibleDayOfMonth)
    {
        $Date = self::BeginningOfDay($Date);
        if (($HowMany > 0 && strtotime("+$HowMany years", $Date) > $Date) || ($HowMany < 0 && strtotime("+$HowMany years", $Date) < $Date)) {
            $YearLater = strtotime("+$HowMany years", $Date);
            if (date("j", $YearLater) != date("j", $Date)) {
                if ($ImpossibleDayOfMonth == 0) {
                    if (($HowMany > 0 && strtotime("+$HowMany years -" . date("j", $YearLater) . " days", $Date) > $Date) || ($HowMany < 0 && strtotime("+$HowMany years -" . date("j", $YearLater) . " days", $Date) < $Date)) {
                        return self::EndOfMonth(strtotime("+$HowMany years -" . date("j", $YearLater) . " days", $Date));
                    }
                    else
                    {
                        return false;
                    }
                } else {
                    return "Impossible";
                }
            }
            return $YearLater;
        }
        else
        {
            return false;
        }
    }

    static function SkipDay($Date, $HowMany)
    {
        if (($HowMany > 0 && strtotime("+$HowMany days", $Date) > $Date) || ($HowMany < 0 && strtotime("+$HowMany days", $Date) < $Date)) {
            return strtotime("+$HowMany days", $Date);
        }
        else
        {
            return false;
        }
    }

    static function ValidateColor($ColorCode)
    {
        if (substr($ColorCode, 0, 1) == "#" && strlen($ColorCode) == 7) {
            $Hex = substr($ColorCode, 1);
            if(ctype_xdigit($Hex))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @return LoginModel
     */
    static function GetLoggedInUser()
    {
        global $LoggedInUser;

        return $LoggedInUser;
    }

    static function floor($input, $decimals)
    {
        if (ctype_digit(strval($input))){
            return $input;
        }
        else {
            return round($input - (5 / pow(10, $decimals + 1)), $decimals);
        }
    }
}