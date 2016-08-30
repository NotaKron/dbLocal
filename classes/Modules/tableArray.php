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
        $content["previousValue"] = 0;
        foreach ($this->_headers as $key) {
            if (!(in_array($key, $this->_arrayFilteredColumns))) {
                $content[$key] = 0;
            }
        }
        return $contentarr = ['default' => $content];
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
        echo '<br>__________________________________________________________________________<br>';
            print_r($this->_headers);
        foreach ($this->_arrayDefaultValues as $key =>$value)
            echo "$key <br>";
      //  }


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
        $key = $row[$columnName];
        $previousColumn = $this->getPreviousCount($columnName);
        $previousKey = $row[$previousColumn];
         if($key!=key($this->_arrayDefaultValues[$columnName])){
             $this->countRepeats($columnName,$row);
         }else if($previousKey!=key($this->_arrayDefaultValues[$columnName])){
             $this->countRepeats($columnName,$row);
         }
    }

    private function countRepeats($columnName, $row)
    {
        $valueCell = $row[$columnName];
        $predColumn = $this->getPredCount($columnName);
        $predKey = $row[$predColumn];
        $prevValue = key($this->arrayDefaultValues[$columnName]);
        $prevCount = $this->arrayDefaultValues[$columnName][$prevValue];
        $count = 0;
        $content=null;
        for ($i = $this->countRow; $i < count($this->_res); ++$i) {
            if (($valueCell == $this->_res[$i][$columnName]) and ($predKey == $this->_res[$i][$predColumn])) {
                $this->summCells($this->_res[i],$columnName);
                $count++;
            } else {
                break;
            }
        }
        if ($this->arrayDefaultValues[$columnName] != 'default') {
            $this->arrayDefaultValues[$columnName] = [$valueCell => $predKey];
            return "<td valign='top' rowspan=\"$count\">$valueCell </td>";
        } else {
            $this->arrayDefaultValues[$columnName] = [$valueCell => $predKey];
            return "<td valign='top' rowspan=\"$count\">$valueCell </td>";
        }
    }

    private function summCells($row,$columnName)
    {
//
    }

    private function getPreviousCount($columnName)
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
}