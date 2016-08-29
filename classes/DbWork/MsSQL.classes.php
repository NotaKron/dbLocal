<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MsSQL
 *
 * @author trkadmin
 */
class MsSQL
{
    private $_alias;
    private $conn;

    function __construct($alias)
    {
        $this->_alias = $alias;
        $conArray = $this->getConnectionString();
        $serverName = $conArray['serverName'];
        $connectionInfo = array("Database" => $conArray['dataBase'], "UID" => $conArray['uid'], "PWD" => $conArray['pwd'], "CharacterSet" => "UTF-8");
        # $connectionInfo=array("UID"=>"sa", "PWD"=>"sa", "Database"=>"RK7_SQL");
        $this->conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($this->conn) {
            echo "Соединение удалось.<br />";
        } else {
            echo "Соединение не удалось, ошибка:";
            die(print_r(sqlsrv_errors(), true));
            echo "<br>";
            print_r($conArray);
        }

    }

    function __destruct()
    {
        sqlsrv_close($this->conn);
    }

    private function getConnectionString()
    {
        $conInfo['serverName'] = $this->splitConString("Data Source=");
        $conInfo['dataBase'] = $this->splitConString("Catalog=");
        $conInfo['uid'] = $this->splitConString("User ID=");
        $conInfo['pwd'] = $this->splitConString("Password=");
        return $conInfo;

    }

    private function splitConString($part)
    {
        $pattern = '#' . $part . '(.*);#Uis';
        if (preg_match($pattern, $this->_alias, $arr))
            return $arr[1];
    }

    public function getResult($sql)
    {
        $stmt = sqlsrv_query($this->conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        sqlsrv_free_stmt($stmt);
        return $data;
    }

    public function showConfigInfo()
    {
        $conArray = $this->getConnectionString();
        echo "Database" . $conArray['dataBase'] . "<br>UID" . $conArray['uid'] . "<br>PWD" . $conArray['pwd'] . "<br>CharacterSet" . "UTF-8";
    }
}
