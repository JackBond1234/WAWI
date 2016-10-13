<?php

include_once("db.php");
include_once("Models/ResponseModel.php");
include_once("Models/LoginModel.php");
include_once("DataAccess/LoginDataAccess.php");
include_once("BusinessObjects/LoginBusinessObject.php");
include_once("Services/LoginService.php");

$error = "";

if (!empty($_POST["username"]) && !empty($_POST["password"]))
{
    $LoginService = new LoginService();

    $RegisterResult = $LoginService->CreateAccount($_POST["username"], $_POST["password"]);

    if ($RegisterResult->Success == true)
    {
        if ($RegisterResult->Data != true) {
            $error .= $RegisterResult->Message . "<br>";
        }
        else
        {
            echo "<meta http-equiv=\"refresh\" content=\"0; URL='login.php?success=true'\" />";
            die();
        }
    }
    else
    {
        $error .= "An error has occurred<br>";
    }
}
else if (!empty($_POST["username"]) || !empty($_POST["password"]))
{
    $error .= "Please input both a username and a password<br>";
}

?>

<html>
<body>
<h1>
    Register
</h1>
<form method="POST" action="register.php">
    <?php echo "<p>" . trim($error, "<br>") . "</p>"; ?>
    <p>Username <input name="username" type="text"></p>
    <p>Password <input name="password" type="password"></p>
    <p><input type="submit"></p>
    <a href="login.php">Login</a>
</form>
</body>
</html>
