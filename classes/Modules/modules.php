<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modules
 *
 * @author ПК
 */
class Modules {
    private $_res;
    private $countRow;
    private $query;
    private $arrayFilteredColumns;
    function __construct($res,$query) {
     $this->_res=$res;
     $this->countRow=0;
     $this->query=$query;
     $this->arrayFilteredColumns =$this->getFilteredColumns();  
    }
    //put your code here
    function printTable($res){
                echo '<table cellpadding="5" cellspacing="0" border="1">';
 echo '<tr>';
$_tmp=$res[0];
$_tmpkey=  array_keys($_tmp);
foreach ($_tmpkey as $key => $value){
    echo "<th> $value</th>"; 
}
echo '</tr>';
foreach ($res as $key => $value) {
    echo "<tr>";
    foreach ($value as $data)
        
        if(is_a($data, 'DateTime'))
           {
           $res= $data->format('Y-m-d H:i:s');
             echo "<td>".$res."</td>";
            }else{
            echo "<td>".$data."</td>";}
    echo "</tr>";
}
echo "</table>";
    }
    function filteredArray($res){
        $count=0;
        $uniqString=NULL;
        foreach ($res as $key=>$value){
            if($uniqString==$value['ORDER_CATEGORY'] )
            {
                $count++;
            }
            elseif ($uniqString!=NULL) {
                echo $uniqString.' повторятется '.$count.'<br>';
                $uniqString=$value['ORDER_CATEGORY'];
                $count=0;
             } else {
                 $uniqString=$value['ORDER_CATEGORY'];
             }
        }
           }
    function getTableHeaders(){
        $_tmp=$this->_res[0];
        $_tmpkey=  array_keys($_tmp);
        print_r($_tmpkey);
    } 
    private function  getFilteredColumns()
    {
        preg_match_all('/\"(.*?)\"/sei', $this->query,$matches);
        return $matches[1];
    }
    function printRows($nameColumn){
        foreach ($this->_res as $key=>$value)
        {
            echo "Это строка №: $this->countRow ";
            print_r($value);
            echo '<br>';
            $this->countRow++;
        }
    }
    private function parceRow($row)
    {
      #  $filtColumn=  $this->getFilteredColumns(f)
    }
    function test()
    {
        print_r($this->arrayFilteredColumns);
    }
           
}
