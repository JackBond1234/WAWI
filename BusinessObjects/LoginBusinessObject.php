<?php

class LoginBusinessObject{
    static function ResultToModelArray($result)
    {
        $return = array();

        while($row = mysqli_fetch_array($result))
        {
            $returnRow = new LoginModel();
            $returnRow->UserId = intval($row["userid"]);
            $returnRow->Username = strval($row["username"]);
            $returnRow->Password = strval($row["password"]);
            $returnRow->SessionString = strval($row["sessionstr"]);
            $return[] = $returnRow;
        }
        return $return;
    }

    static function FirstResultToModel($result)
    {
        $returnRow = null;

        if($row = mysqli_fetch_array($result)) {
            $returnRow = new LoginModel();
            $returnRow->UserId = intval($row["userid"]);
            $returnRow->Username = strval($row["username"]);
            $returnRow->Password = strval($row["password"]);
            $returnRow->SessionString = strval($row["sessionstr"]);
        }

        return $returnRow;
    }
}