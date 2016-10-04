<?php

/**
 * Created by PhpStorm.
 * User: Admindb
 * Date: 08.09.2016
 * Time: 8:46
 */
class massiv
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

    private function getFilteredColumnsArray()
    {
        preg_match_all('/\"(.*?)\"/sei', $this->_query, $matches);
        return $matches[1];
    }

    private function getContentArray()
    {

        $content["count"] = 0;
        $content["previousValue"] = 'default';
        $uniqContent = $this->getUniqContent();
        return $contentarr[0] = ['default' => array_merge($content, $uniqContent)];
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
        $this->countedRows();
        $tmp = $this->getCountArray();
        echo 'Начинаем <br>____________________________________________________________________________________________<br>';
        echo "<br>________________________________________________________________________________________<br>";
        //  $this->getNextRowspan(83, 'ORDER_CATEGORY');
        $this->goOnArray();
        echo "<br>________________________________________________________________________________________<br>";

        foreach ($tmp as $key) {
            print_r($key);
            echo "<br>";
        }
    }

    private function countedArrayDefault()
    {
        for ($i = 0; $i < (count($this->_arrayDefaultValues) - count($this->_arrayFilteredColumns)); $i++) {
            $columnName = key($this->_arrayDefaultValues[$i]);
            $cellValue = key($this->_arrayDefaultValues[$i][$columnName]);
            $count = $this->_arrayDefaultValues[$i][$columnName][$cellValue]['count'];
            $this->_arrayDefaultValues[$i][$columnName][$cellValue]['count'] = $count + $this->pereborMassiva($i, key($this->_arrayDefaultValues[$i]));
        }
    }

    private function pereborMassiva($i, $columnName)
    {
        // $columnName=end($this->_arrayFilteredColumns);
        $count = 0;
        $repeats = 0;
        for ($i; $i < (count($this->_arrayDefaultValues) - count($this->_arrayFilteredColumns)); $i++) {
            //  echo "$i: ".$columnName."  ".key($this->_arrayDefaultValues[$i])."<br>";
            if (($repeats > 1) or ($this->reverceMathod($columnName, key($this->_arrayDefaultValues[$i])) == true)) break;
            else {
                if (($columnName != key($this->_arrayDefaultValues[$i]))) $count++;
                else $repeats++;
            }
        }
        return $count;
    }

    private function reverceMathod($columnName, $previous)
    {
        $result = false;
        $index = array_search($columnName, $this->_arrayFilteredColumns);
        $previousIndex = array_search($previous, $this->_arrayFilteredColumns);
        if ($previousIndex < $index) $result = true;
        return $result;
    }


    private function printTable($res)
    {
        echo '<table cellpadding="5" cellspacing="0" border="1">';
        echo '<tr>';
        $_tmp = $res[0];
        $_tmpkey = array_keys($_tmp);
        foreach ($_tmpkey as $key => $value) {
            echo "<th> $value</th>";
        }
        echo '</tr>';

        $this->countedRows();
        $this->_countRow = 0;
        $this->printRows();

        echo "</table>";
    }

    private function printRows()
    {
        foreach ($this->_res as $key => $value) {
            echo "<tr>";
            echo $this->drawCells($value);
            echo "</tr>";
            $this->_countRow++;
        }
    }

    private function drawCells($row)
    {
        $stringTr = null;
        foreach ($row as $cell => $key) {
            $keyCell = array_search($key, $row);
            if (in_array($keyCell, $this->_arrayFilteredColumns)) {
                $stringTr = $stringTr . $this->getRowString($keyCell, $row);
            } else $stringTr = $stringTr . "<td>" . $key . "</td>";
        }
        return $stringTr;
    }

    private function getCellsCount($columnName, $value)
    {
        foreach ($this->_arrayDefaultValues as $row => $column) {
            if (key($column) == $columnName)
                foreach ($column as $cells) {
                    if ($value == key($cells)) return "<td valign='top' rowspan=\"" . $cells[key($cells)]['count'] . "\">$value</td>";
                }
        }
    }

    private function getRowString($columnName, $row)
    {
        $key = $row[$columnName];
        $previousColumn = $this->getPreviousCount($columnName);
        $previousKey = $row[$previousColumn];
        $tmp = $this->getCheckedString($columnName, $key, $previousKey);
        if (($tmp == null) or ($key != key($tmp[$columnName]))) {
            return $this->getCellsCount($columnName, $key);;
        } else if ($previousKey != $tmp[$columnName][$key]['previousValue']) {
            return $this->getCellsCount($columnName, $key);;
        } else return '';

    }


    private function countedRows()
    {
        foreach ($this->_res as $key => $value) {
            $this->parceRow($value);
            $this->_countRow++;
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

    private function getCheckedString($columnName, $cell, $previousCell)
    {

        foreach ($this->_arrayDefaultValues as $key => $value) {
            if (key($value) == $columnName) {
                $tmp = $value[key($value)];

                if ((key($tmp) == $cell) and ($tmp[$cell]["previousValue"] == $previousCell)) {

                    return $value;
                }
            }
        }
    }

    private function checkRepeat($columnName, $row)
    {
        $key = $row[$columnName];
        $previousColumn = $this->getPreviousCount($columnName);
        $previousKey = $row[$previousColumn];
        $tmp = $this->getCheckedString($columnName, $key, $previousKey);
        if (($tmp == null) or ($key != key($tmp[$columnName]))) {
            return $this->countRepeats($columnName, $row);
        } else if ($previousKey != $tmp[$columnName][$key]['previousValue']) {
            return $this->countRepeats($columnName, $row);
        } else return '';
    }

    private function countRepeats($columnName, $row)
    {
        $valueCell = $row[$columnName];
        $predColumn = $this->getPreviousCount($columnName);
        $predKey = $row[$predColumn];
        $count = 0;
        $content = $this->getUniqContent();
        for ($i = $this->_countRow; $i < count($this->_res); ++$i) {
            if (($valueCell == $this->_res[$i][$columnName]) and ($predKey == $this->_res[$i][$predColumn])) {
                $content = $this->summCells($this->_res[$i], $content);
                $count++;
            } else {
                break;
            }
        }
        $tmp = $this->getCheckedString($columnName, $valueCell, $predKey);
        if (($tmp == null) or (key($tmp[$columnName]) != 'default')) {
            $this->fillContent($valueCell, $count, $predKey, $content, $columnName);
        }
    }

    private function summCells($row, $content)
    {
        foreach ($content as $key => $value) {
            $content[$key] = $value + $row[$key];
        }
        return $content;
    }

    private function getPreviousCount($columnName)
    {
        $index = $columnName;
        foreach ($this->_arrayDefaultValues as $key => $value) {
            if ($columnName != $key) {
                $index = $key;

            } else {
                return $index;
            }
        }
        return $index;
    }

    private function fillContent($cell, $count, $previousValue, $content, $columnName)
    {
        $tmp = null;
        $contentArray["count"] = $count;
        $contentArray["previousValue"] = $previousValue;
        foreach ($content as $key => $value) {
            $contentArray[$key] = $value;
        }
        $tmp[$columnName] = [$cell => $contentArray];
        array_push($this->_arrayDefaultValues, $tmp);


    }

    private function getUniqContent()
    {
        foreach ($this->_headers as $key) {
            if (!(in_array($key, $this->_arrayFilteredColumns))) {
                $content[$key] = 0;
            }
        }
        return $content;
    }

    private function onMassive()
    {
        for ($i = 0; $i < count($this->_res); ++$i) {
            foreach ($this->_res[$i] as $key => $value) {
                $this->checkOnMassiv($i, $key);
            }
            echo "<br>";
        }
    }

    private function checkOnMassiv($i, $columnName)
    {
        echo "$columnName <br>";
        if (in_array($columnName, $this->_arrayFilteredColumns)) {
            $this->getNextRowspan($i, $columnName);
        }
    }

    private function filterValueArray($var)
    {
        foreach ($this->_arrayDefaultValues as $value) {
            if (key($value) == $var) {
                $cell = key($value[$var]);
                $tmp[] = $value[$var][$cell]['count'];
            }
        }
        return $array = [$var => $tmp];
    }

    private function getCountArray()
    {
        $countArray[] = [0 => 0];
        foreach ($this->_arrayFilteredColumns as $value) {
            $countArray[] = $this->filterValueArray($value);
        }
        return $countArray;
    }

    private function getNextRowspan($i, $columnName,$iter)
    {
        $arrayValueCount = $this->getCountArray();
        foreach ($arrayValueCount as $key => $value) {
            if (key($value) == $columnName) $tmp = $value;
        }
        if ($i == 0) {
            return array_shift($tmp[$columnName]);
        }
        if($iter==count($tmp[$columnName])-1) return null;
        for ($count=$iter; $count<count($tmp[$columnName]);$count++){
            if($i==$tmp[$columnName][$count]) return $tmp[$columnName][$count+1];
    }
    }


    private function getTmpArray()
    {
        foreach ($this->_arrayFilteredColumns as $key) {
            $tmp[$key] = ["rowCount" => 0, "summ" => 0,"iter"=>-1];
        }
        return $tmp;
    }

    private function goOnArray()
    {
        $tmp = $this->getTmpArray();
        foreach ($this->_res as $key => $value) {
            foreach ($value as $columnName => $cell) {

                if ((in_array($columnName, $this->_arrayFilteredColumns)) and ($tmp[$columnName]['summ'] == $key)) {
                    $rowspan = $this->getNextRowspan($tmp[$columnName]['rowCount'], $columnName,$tmp[$columnName]['iter']);
                //  echo"Send: ".$tmp[$columnName]['rowCount']."  Recive: $rowspan <br>";
                    $tmp[$columnName]['summ'] += $rowspan;
                    $tmp[$columnName]['rowCount'] = $rowspan;
                    $tmp[$columnName]['iter'] ++;
                    if($columnName=='CURRENCY')
                    echo "$key => $columnName:  Rowspan:=> $rowspan  Summ:=>> " . $tmp[$columnName]['summ'] . "<br>";
                }
            }
        }
    }
}
