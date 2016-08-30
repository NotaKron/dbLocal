<?php

/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 02.08.2016
 * Time: 10:04
 */
class tableArray
{
    private $_res;
    private $_query;
    private $_arrayFilteredColumns;
    private $_arrayDefaultValues;
    private $_headers;
    private $_count = 0;

    function __construct($res, $query)
    {
        $this->_res = $res;
        $this->_query = $query;
        $this->_arrayFilteredColumns = $this->getFilteredColumnsArray();
        $this->_headers = array_keys($this->_res[0]);
    }

    private function getFilteredColumnsArray()
    {
        preg_match_all('/\"(.*?)\"/sei', $this->_query, $matches);
        return $matches[1];
    }

    private function getContentArray()
    {
        $content["count"] = 0;
        $content["predValue"] = 0;
        foreach ($this->_headers as $key) {
            if (!(in_array($key, $this->_arrayFilteredColumns))) {
                $content[$key] = 0;
            }
        }
        return $content;
    }

    private function getDefaultArray()
    {

        foreach ($this->_headers as $key) {
            if (in_array($key, $this->_arrayFilteredColumns))
                $this->_arrayDefaultValues[$key] = $this->getContentArray();
        }
    }

    public function test()
    {
        $this->getDefaultArray();
        print_r($this->_arrayDefaultValues);
    }

    private function printRows()
    {
        foreach ($this->_res as $key => $value) {
            $this->parceRow($value);
            $this->_count++;
        }

    }

    private function parceRow($row)
    {
        foreach ($row as $cell => $key) {
            $keyCell = array_search($key, $row);
            if (in_array($keyCell, $this->_arrayFilteredColumns)) {
                $this->checkRepeat($keyCell, $row);
            }
        }
    }

    private function checkRepeat($columnName, $row)
    {
        //
    }

    private function summCells($row)
    {

    }
}