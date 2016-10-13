<?php

class LoginService {

    private $LoginDataAccess;

    function __construct()
    {
        $this->LoginDataAccess = new LoginDataAccess();
    }

    /**
     * @param string $Username
     * @param string $RawPassword
     * @return ResponseModel
     */
    public function CreateAccount($Username, $RawPassword)
    {
        $Response = new ResponseModel();

        try
        {
            if (!$this->LoginDataAccess->DoesExist($Username)) {
                $LoginModel = new LoginModel();
                $LoginModel->Username = $Username;

                $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
                $salt = str_replace("+", ".", $salt);
                $param = '$' . implode('$', array(
                        "2y",
                        str_pad(11, 2, "0", STR_PAD_LEFT),
                        $salt
                    ));

                $LoginModel->Password = crypt($RawPassword, $param);

                $this->LoginDataAccess->Insert($LoginModel);

                $Response->Data = true;
                $Response->Message = "The account was created successfully";
            }
            else
            {
                $Response->Data = false;
                $Response->Message = "The account was not created due to overlapping usernames";
            }
            $Response->Success = true;
        }
        catch (Exception $e)
        {
            $Response->Success = false;
            $Response->Message = $e->getMessage();
        }

        return $Response;
    }

    public function ValidateLogin($Username, $RawPassword)
    {
        $Response = new ResponseModel();

        try
        {
            $LoginModel = LoginBusinessObject::FirstResultToModel($this->LoginDataAccess->SelectByUsername($Username));

            $Response->Success = true;
            $Response->Data = crypt($RawPassword, $LoginModel->Password)==$LoginModel->Password;
            $Response->Message = "The password was evaluated successfully";
        }
        catch (Exception $e)
        {
            $Response->Success = false;
            $Response->Message = $e->getMessage();
        }

        return $Response;
    }

    public function CreateSessionKey($Username)
    {
        $Response = new ResponseModel();

        try
        {
            if (session_status() == PHP_SESSION_ACTIVE) {
                $LoginModel = LoginBusinessObject::FirstResultToModel($this->LoginDataAccess->SelectByUsername($Username));

                $LoginModel->SessionString = md5(uniqid(rand(), true));

                $_SESSION["sessionstr"] = $LoginModel->SessionString;

                $this->LoginDataAccess->Update($LoginModel);

                $Response->Success = true;
                $Response->Message = "Session was successfully created";
            }
            else
            {
                $Response->Success = false;
                $Response->Message = "A PHP session is not active, so the session key cannot be saved";
            }
        }
        catch (Exception $e)
        {
            $Response->Success = false;
            $Response->Message = $e->getMessage();
        }

        return $Response;
    }

    public function CheckSessionKey()
    {
        $Response = new ResponseModel();

        try
        {
            if (session_status() == PHP_SESSION_ACTIVE)
            {
                $LoginModel = null;
                if (isset($_SESSION["sessionstr"])) {
                    $LoginModel = LoginBusinessObject::FirstResultToModel($this->LoginDataAccess->SelectBySessionKey($_SESSION["sessionstr"]));
                }

                $Response->Success = true;
                $Response->Data = $LoginModel;
                $Response->Message = "The session key was validated successfully";
            }
            else
            {
                $Response->Success = false;
                $Response->Message = "A PHP session is not active, so the session key cannot be verified";
            }
        }
        catch(Exception $e)
        {
            $Response->Success = false;
            $Response->Message = $e->getMessage();
        }
        return $Response;
    }
}