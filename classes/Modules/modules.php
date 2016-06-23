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
class Modules
{
    private $_res;
    private $countRow = 0;
    private $query;
    private $arrayFilteredColumns;
    private $arrayDefaultValues;

    function __construct($res, $query)
    {
        $this->_res = $res;
        $this->countRow = 0;
        $this->query = $query;
        $this->arrayFilteredColumns = $this->getFilteredColumnsArray();
        $this->arrayDefaultValues = $this->getDefaultArray();
    }

    //put your code here
    function printTable($res)
    {
        echo '<table cellpadding="5" cellspacing="0" border="1">';
        echo '<tr>';
        $_tmp = $res[0];
        $_tmpkey = array_keys($_tmp);
        foreach ($_tmpkey as $key => $value) {
            echo "<th> $value</th>";
        }
        echo '</tr>';

        $this->printRows();

        echo "</table>";
    }

    private function filteredArray($columnName, $value)
    {
        if ($value != key($this->arrayDefaultValues[$columnName])) {
            $uniqArray = $this->countRepeats($columnName, $value);
            print_r($uniqArray);
        }

    }

    function getTableHeaders()
    {
        $_tmp = $this->_res[0];
        $_tmpkey = array_keys($_tmp);
        print_r($_tmpkey);
    }

    private function getFilteredColumnsArray()
    {
        preg_match_all('/\"(.*?)\"/sei', $this->query, $matches);
        return $matches[1];
    }

    function printRows()
    {
        foreach ($this->_res as $key => $value) {
            $this->countRow++;
            echo "<tr>";
            echo $this->parceRow($value);
            echo "</tr>";

        }
    }

    private function parceRow($row)
    {
        $stringTr = null;
        foreach ($row as $cell => $key) {
            $keyRow = array_search($key, $row);
            if (in_array($keyRow, $this->arrayFilteredColumns)) {
                $stringTr = $stringTr . $this->checkRepeat($keyRow, $key);
            } else $stringTr = $stringTr . "<td>" . $key . "</td>";
        }
        return $stringTr;
    }

    function test()
    {
        //$this->printTable($this->_res);
        print_r($this->getPredCount('ORDER_CATEGORY'));


    }

    private function checkRepeat($columnName, $key)
    {
        if ($key != key($this->arrayDefaultValues[$columnName])) {
            return $this->countRepeats($columnName, $key);
        } else return '';
    }

    private function filteredColumns($columnName, $cell)
    {
        $sizeArray = count($this->arrayFilteredColumns);

        for ($this->countRow; $this->countRow < $sizeArray; ++$this->countRow) {
            if (key($this->arrayDefaultValues[$columnName]) == $cell) {
                // $countRepeats=
            }
        }
    }

    private function getDefaultArray()
    {
        foreach ($this->arrayFilteredColumns as $key) {
            $this->arrayDefault[$key] = ['default' => 0];
        }
        return $this->arrayDefault;
    }

    private function countRepeats($columnName, $valueCell)
    {
        $prevValue = key($this->arrayDefaultValues[$columnName]);
        $prevCount = $this->arrayDefaultValues[$columnName][$prevValue] + $this->countRow;
        if (0 != $prevCount) $prevCount--;
        $count = 0;
        for ($i = $this->countRow; $i < count($this->_res); ++$i) {
            if ($valueCell == $this->_res[$i][$columnName]) {
                $count++;
            } else  break;
        }
        $count++;
        $this->arrayDefaultValues[$columnName] = [$valueCell => $count];
        return "<td valign='top' rowspan=\"$count\">$valueCell </td>";
    }

    private function getPredCount($columnName)
    {
        $index = Null;
        foreach ($this->arrayDefaultValues as $key => $value) {
            if ($columnName != $key) {
                               $index = current($value);

            } else {
                     return $index;
            }
        }
        return $index;
    }

}