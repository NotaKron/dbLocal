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
    private $countRow = 0;

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
        $uniqContent=$this->getUniqContent();
        return $contentarr = ['default' => array_merge($content,$uniqContent)];
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
        echo"<br>________________________________________________________________________________________________________<br>";
        $this->printRows();
        $i=1;
      foreach ($this->_arrayDefaultValues as $value){
          echo "$i: ";
          print_r($value);
          echo"<br>";
          $i++;
      }


    }

    private function printRows()
    {
        foreach ($this->_res as $key => $value) {
            $this->parceRow($value);
            $this->countRow++;
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
        if ($key != key($this->_arrayDefaultValues[$columnName])) {
            $this->countRepeats($columnName, $row);
        } else if ($previousKey != key($this->_arrayDefaultValues[$columnName])) {
            $this->countRepeats($columnName, $row);
        }
    }

    private function countRepeats($columnName, $row)
    {
        $valueCell = $row[$columnName];
        $predColumn = $this->getPreviousCount($columnName);
        $predKey = $row[$predColumn];
        $count = 0;
        $content=$this->getUniqContent();
            for ($i = $this->countRow; $i < count($this->_res); ++$i) {
                if (($valueCell == $this->_res[$i][$columnName]) and ($predKey == $this->_res[$i][$predColumn])) {
                    $content = $this->summCells($this->_res[$i],  $content);
                    $count++;
                } else {
                    break;
                }
            }
           if (key($this->_arrayDefaultValues[$columnName]) != 'default') {
                                $this->fillContent($valueCell, $count, $predKey, $content,$columnName);

              } else {

                 $this->fillContent($valueCell, $count, $predKey, $content,$columnName);

               }


        }


    private function summCells($row, $content)
    {
       foreach ($content as $key=>$value) {
           $content[$key]=$value+$row[$key];
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

    private function fillContent($value, $count, $previousValue, $content, $columnName)
    {

        $contentArray["count"] = $count;
        $contentArray["previousValue"] = $previousValue;
        foreach ($content as $key => $value) {
            $contentArray[$key] = $value;
        }
        $tmpArray[$columnName ]=[$value => $contentArray];
        array_push($this->_arrayDefaultValues,$tmpArray);
    }
    private function getUniqContent(){
        foreach ($this->_headers as $key) {
            if (!(in_array($key, $this->_arrayFilteredColumns))) {
                $content[$key] = 0;
            }
        }
    return $content;
    }
}