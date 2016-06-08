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
    private $countRow=0;
    private $query;
    private $arrayFilteredColumns;
    private $arrayDefaultValues;
    function __construct($res,$query) {
        $this->_res=$res;
        $this->countRow=0;
        $this->query=$query;
        $this->arrayFilteredColumns =$this->getFilteredColumnsArray();
        $this->arrayDefaultValues=$this->getDefaultArray();
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
    private function filteredArray($columnName,$value){
        if($value!=key($this->arrayDefaultValues[$columnName]))
        {
            $uniqArray=$this->countRepeats($columnName,$value);
            print_r($uniqArray);
        }

    }
    function getTableHeaders(){
        $_tmp=$this->_res[0];
        $_tmpkey=  array_keys($_tmp);
        print_r($_tmpkey);
    }
    private function  getFilteredColumnsArray()
    {
        preg_match_all('/\"(.*?)\"/sei', $this->query,$matches);
        return $matches[1];
    }
    function printRows(){
        foreach ($this->_res as $key=>$value)
        {
            $this->parceRow($value);
            $this->countRow++;
        }
    }
    private function parceRow($row)
    {
        foreach ($row as $cell=>$key) {
            $keyRow= array_search($key,$row);
            if(in_array($keyRow,$this->arrayFilteredColumns)){
                $this->countRepeats($keyRow);
            }
            else echo $keyRow.' не фильтруем! <br>';
        }
    }
    function test()
    {
        $count=count($this->_res);
    for($i=0;$i<$count;++$i)
    {
        echo 'Stroka # '.$i;
        print_r($this->_res[$i]['ORDER_CATEGORY']);
        echo '<br>';
    }
    }
    private function filteredColumns($columnName,$cell){
        $sizeArray=count($this->arrayFilteredColumns);

        for($this->countRow;$this->countRow<$sizeArray;++$this->countRow){
            if(key($this->arrayDefaultValues[$columnName])==$cell){
              // $countRepeats=
            }
        }
    }
    private function getDefaultArray(){
        foreach ($this->arrayFilteredColumns as $key){
            $this->arrayDefault[$key]=['default'=>0];
        }
        return $this->arrayDefault;
    }
    private function countRepeats($columnName,$valueCell){

       for ($i=$this->countRow;i<count($this->_res);++$i)
       {

       }
        /*$count=0;
        $uniqString=NULL;
        foreach ($this->_res as $key=>$value){
            if($uniqString==$value[$columnName] )
            {
                $count++;
            }
            elseif ($uniqString!=NULL) {
                echo $uniqString.' повторятется '.$count.'<br>';
                $uniqString=$value[$columnName];
                $count=0;
            } else {
                $uniqString=$value[$columnName];
            }
        }*/
    }
}
