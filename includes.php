<?php
session_start();

include_once("Utility.php");
include_once("config.php");
include_once("connections.php");
include_once("db.php");

include_once("Models/ResponseModel.php");
include_once("Models/LoginModel.php");
include_once("DataAccess/LoginDataAccess.php");
include_once("BusinessObjects/LoginBusinessObject.php");
include_once("Services/LoginService.php");

$LoginService = new LoginService();

$CheckSessionResult = $LoginService->CheckSessionKey();

$LoggedInUser = array();

if ($CheckSessionResult->Success != true || $CheckSessionResult->Data == null) {
    echo "<meta http-equiv=\"refresh\" content=\"0; URL='".Config::ROOT_URL."/login.php'\" />";
    die();
}
else
{
    /** @var LoginModel $LoggedInUser */
    $LoggedInUser = $CheckSessionResult->Data;
}

include_once("Models/CategoriesModel.php");
include_once("Models/GlobalsModel.php");
include_once("Models/DateModel.php");
include_once("Models/RebalanceModel.php");


include_once("DataAccess/CategoriesDataAccess.php");
include_once("DataAccess/GlobalsDataAccess.php");
include_once("DataAccess/DateDataAccess.php");
include_once("DataAccess/RebalanceDataAccess.php");


include_once("BusinessObjects/CategoriesBusinessObject.php");
include_once("BusinessObjects/GlobalsBusinessObject.php");
include_once("BusinessObjects/DateBusinessObject.php");
include_once("BusinessObjects/RebalanceBusinessObject.php");


include_once("Services/CategoriesService.php");
include_once("Services/GlobalsService.php");

$GlobalsDataAccess = new GlobalsDataAccess();
$GlobalsResult = $GlobalsDataAccess->Select();
if ($GlobalsResult->num_rows == 0){
    $DateDataAccess = new DateDataAccess();
    $DateModel = new DateModel();
    $DateModel->DateId = $DateDataAccess->Insert($DateModel);
    $GlobalsModel = new GlobalsModel();
    $GlobalsModel->Date = $DateModel;
    $GlobalsDataAccess->Insert($GlobalsModel);
    echo "<meta http-equiv=\"refresh\" content=\"0; URL='".Config::ROOT_URL."/profile.php'\" />";
    die();
}