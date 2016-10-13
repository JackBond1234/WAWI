<?php

class db{
    /** @var mysqli $conn */
    public $conn = null;

    public function Query($Query)
    {
        $this->Connect();
        $result = $this->conn->query($Query);
        echo $this->conn->error;
        return $result;
    }

    private function Connect()
    {
        if (!isset($this->conn)) {
            $this->conn = new mysqli(Connections::HOST, Connections::USERNAME, Connections::PASSWORD, Connections::DB);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
    }
}
