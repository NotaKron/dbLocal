<?php

/**
 * Created by PhpStorm.
 * User: Admindb
 * Date: 15.11.2016
 * Time: 10:23
 */
class test
{
    private $_res;
    private $_query;
    private $_arrayFilteredColumns;
    private $_arrayDefaultValues;
    private $_headers;
    private $_countRow = 0;

    function __construct($res, $query)
    {
        $this->_res = $res;
        $this->_query = $query;
        $this->_arrayFilteredColumns = $this->getFilteredColumnsArray();
        $this->_headers = array_keys($this->_res[0]);
    }

    private function getDefaultArray()
    {

        foreach ($this->_headers as $key) {
            if (in_array($key, $this->_arrayFilteredColumns))
                $this->_arrayDefaultValues[$key] = $this->getContentArray();
        }
    }
    private function getContentArray()
    {

        $content["count"] = 0;
        $content["previousValue"] = 0;
        $uniqContent=$this->getUniqContent();
        return $contentarr = ['default' => array_merge($content,$uniqContent)];
    }
    private function getUniqContent(){
        foreach ($this->_headers as $key) {
            if (!(in_array($key, $this->_arrayFilteredColumns))) {
                $content[$key] = 0;
            }
        }
        return $content;
    }

    public function test(){
        $this->getDefaultArray();
        foreach ($this->_arrayDefaultValues as $key){
            print_r($key);
            echo "<br>";
        }
    }
}