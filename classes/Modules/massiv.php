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
        $this->printRows();
        echo '<br>____________________________________________________________________________________________<br>';
        foreach ($this->_arrayDefaultValues as $key => $value) {
            if (key($value) == "CURRENCY") {
                $tmp=$value[key($value)];
                if(key($tmp)=="VISA"){
                print_r($value);
                echo'<br>';
            }
        }
    }
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

        $this->printRows();

        echo "</table>";
    }

    private function printRows()
    {
        foreach ($this->_res as $key => $value) {
            // echo "<tr>";
            $this->parceRow($value);
            // echo "</tr>";
            $this->_countRow++;
        }

    }

    private function parceRow($row)
    {
        // $stringTr = null;
        foreach ($row as $cell => $key) {
            $keyCell = array_search($key, $row);
            if (in_array($keyCell, $this->_arrayFilteredColumns)) {
                $this->checkRepeat($keyCell, $row);
            } //else $stringTr=$stringTr."<td>".$key."</td>";
        }
        //return $stringTr;
    }
    private function getCheckedString($columnName,$cell,$previousCell){
        foreach ($this->_arrayDefaultValues as $key => $value) {
            if (key($value) == $columnName) {
                $tmp=$value[key($value)];
                if((key($tmp)==$cell) and ($tmp[$cell]["previousValue"]==$previousCell)){
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
        $tmp=$this->getCheckedString($columnName,$key,$previousKey);
        print_r($tmp);
           if ($key != key($tmp[$columnName])) {
        // if ($key != key($this->_arrayDefaultValues[$columnName])) {
            return $this->countRepeats($columnName, $row);
       } else if ($previousKey != $tmp[$columnName][$key]['previousValue']) {
               //} else if ($previousKey != $this->_arrayDefaultValues[$columnName][$key]['previousValue']) {
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
        $tmp=$this->getCheckedString($columnName, $valueCell ,$predKey);
       if (key($tmp[$columnName]) != 'default') {
        //  if (key($this->_arrayDefaultValues[$columnName]) != 'default') {
            $this->fillContent($valueCell, $count, $predKey, $content, $columnName);

         //   return "<td valign='top' rowspan=\"$count\">$valueCell </td>";
        } else {
            $this->fillContent($valueCell, $count, $predKey, $content, $columnName);
         //   return "<td valign='top' rowspan=\"$count\">$valueCell </td>";

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
}