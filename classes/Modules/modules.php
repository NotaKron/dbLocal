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

            echo "<tr>";
            echo $this->parceRow($value);
            echo "</tr>";
            $this->countRow++;
        }
    }

    private function parceRow($row)
    {
        $stringTr = null;
        foreach ($row as $cell => $key) {
            $keyRow = array_search($key, $row);
            if (in_array($keyRow, $this->arrayFilteredColumns)) {
                $stringTr = $stringTr . $this->checkRepeat($keyRow, $row);
            } else $stringTr = $stringTr . "<td>" . $key . "</td>";
        }
        return $stringTr;
    }

    function test()
    {
        $this->printTable($this->_res);
        /*
                $this->arrayDefaultValues['First']=['first'=>11];
                $this->arrayDefaultValues['Second']=['second'=>22];
                $this->arrayDefaultValues['Third']=['third'=>33];
            foreach ($this->_res as $key =>$valye) {
                echo($valye['ORDER_CATEGORY']);
                echo '<br>';
                }*/
//        echo(current($this->arrayDefaultValues[$this->getPredCount('Second')]));
//        echo '<br> perenos';
//        echo prev($this->arrayDefaultValues['Second']);

    }

    private function checkRepeat($columnName, $row)
    {
        $key = $row[$columnName];
        $predColumn=$this->getPredCount($columnName);
        $predKey=$row[$predColumn];
        if (($key != key($this->arrayDefaultValues[$columnName]))) {
            return $this->countRepeats($columnName, $row);
        } else if($predKey!=$this->arrayDefaultValues[$columnName][$key])
            return $this->countRepeats($columnName, $row);
        else
            return '';
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

    private function countRepeats($columnName,$row )
    {
        $valueCell = $row[$columnName];
        $predColumn=$this->getPredCount($columnName);
        $predKey=$row[$predColumn];
        $prevValue = key($this->arrayDefaultValues[$columnName]);
        $prevCount = $this->arrayDefaultValues[$columnName][$prevValue];
        //  if (0 != $prevCount) $prevCount--;
        $count = 0;
        for ($i = $this->countRow; $i < count($this->_res); ++$i) {
            if (($valueCell == $this->_res[$i][$columnName]) and ($predKey==$this->_res[$i][$predColumn] )) {
                $count++;
                $this->arrayDefaultValues[$columnName] = [$valueCell => ($count + $prevCount)];
            } else  {
              // $this->zeroArrayDefaultValues($columnName);
                break;
            }
        }
        $this->arrayDefaultValues[$columnName] = [$valueCell => $predKey];
        return "<td valign='top' rowspan=\"$count\">$valueCell </td>";
    }

    private function getPredCount($columnName)
    {
        $index = $columnName;
        foreach ($this->arrayDefaultValues as $key => $value) {
            if ($columnName != $key) {
                $index = $key;

            } else {
                return $index;
            }
        }
        return $index;
    }

    private function checkCount($startKey, $count)
    {
        $prevValue = key($this->arrayDefaultValues[$startKey]);
        $prevCount = $this->arrayDefaultValues[$startKey][$prevValue];
        $summ = $count + $prevCount;
        $check = current($this->arrayDefaultValues[$this->getPredCount($startKey)]);
        foreach ($this->arrayDefaultValues as $key => $value) {
            if ($this->countRow >= current($value)) {
                $this->zeroArrayDefaultValues($key);
            }

        }
    }

    private function zeroArrayDefaultValues($startKey)
    {

        for ($i = 0; $i < count($this->arrayDefaultValues); $i++) {
            if ($startKey == key($this->arrayDefaultValues))
                for ($k = $i; $k < count($this->arrayDefaultValues);++ $k) {
                    $this->arrayDefaultValues[key($this->arrayDefaultValues)] = ['default' => 0];
                    next($this->arrayDefaultValues);
                }
            else next($this->arrayDefaultValues);
        }
        /* echo "Stroka $i: ";
         print_r(key($this->arrayDefaultValues));
         next($this->arrayDefaultValues);
         echo '<br>';*/

    }


}