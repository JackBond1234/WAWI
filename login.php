<?php
session_start();

include_once("db.php");
include_once("Models/ResponseModel.php");
include_once("Models/LoginModel.php");
include_once("DataAccess/LoginDataAccess.php");
include_once("BusinessObjects/LoginBusinessObject.php");
include_once("Services/LoginService.php");

$error = "";
if (isset($_GET["success"]) && $_GET["success"] == "true")
{
    $error = "Account created successfully";
}

if (!empty($_POST["username"]) && !empty($_POST["password"]))
{
    $LoginService = new LoginService();

    $ValidationResult = $LoginService->ValidateLogin($_POST["username"], $_POST["password"]);

    if ($ValidationResult->Success == true)
    {
        if ($ValidationResult->Data != true)
        {
            $error .= "Invalid Username or Password<br>";
        }
        else
        {
            $SessionResult = $LoginService->CreateSessionKey($_POST["username"]);
            if ($SessionResult->Success == true) {
                echo "<meta http-equiv=\"refresh\" content=\"0; URL='index.php'\" />";
                die();
            }
            else
            {
                $error .= "Unable to log you in at the moment<br>";
            }
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
    Login
</h1>
<form method="POST" action="login.php">
    <?php echo "<p>" . trim($error, "<br>") . "</p>"; ?>
    <p>Username <input name="username" type="text"></p>
    <p>Password <input name="password" type="password"></p>
    <p><input type="submit"></p>
    <a href="register.php">Register</a>
</form>
</body>
</html>
