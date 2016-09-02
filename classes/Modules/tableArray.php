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
      //  print_r($this->_arrayDefaultValues["ORDER_CATEGORY"]['default'] ['previousValue'] );
        $this->printTable($this->_res);

        echo "<br>_________________________________________________________________________________________________________<BR>";
        print_r($this->_arrayDefaultValues["ORDER_CATEGORY"]['АнглийскийПаб Заказ'] ['previousValue'] );
        /*

        echo"<br>________________________________________________________________________________________________________<br>";
        $this->printRows();
        $i=1;
        foreach ($this->_arrayDefaultValues as $value){
            echo "$i: ";
            print_r($value);
            echo"<br>";
            $i++;
        }*/


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
            echo "<tr>";
            echo $this->parceRow($value);
            echo "</tr>";
            $this->_countRow++;
        }

    }
    private function parceRow($row)
    {
        $stringTr = null;
        foreach ($row as $cell => $key) {
            $keyCell = array_search($key, $row);
            if (in_array($keyCell, $this->_arrayFilteredColumns)) {
                $stringTr=$stringTr.$this->checkRepeat($keyCell, $row);
            }else $stringTr=$stringTr."<td>".$key."</td>";
        }
       return $stringTr;
    }
    private function checkRepeat($columnName, $row)
    {
        $key = $row[$columnName];
        $previousColumn = $this->getPreviousCount($columnName);
        $previousKey = $row[$previousColumn];
        if ($key != key($this->_arrayDefaultValues[$columnName])) {
            return $this->countRepeats($columnName, $row);
        } else if ($previousKey != $this->_arrayDefaultValues[$columnName][$key]['previousValue']) {
           return $this->countRepeats($columnName, $row);
        }
        else return'';
    }
    private function countRepeats($columnName, $row)
    {
        $valueCell = $row[$columnName];
        $predColumn = $this->getPreviousCount($columnName);
        $predKey = $row[$predColumn];
        $count = 0;
        $content=$this->getUniqContent();
            for ($i = $this->_countRow; $i < count($this->_res); ++$i) {
                if (($valueCell == $this->_res[$i][$columnName]) and ($predKey == $this->_res[$i][$predColumn])) {
                    $content = $this->summCells($this->_res[$i],  $content);
                    $count++;
                } else {
                    break;
                }
            }
           if (key($this->_arrayDefaultValues[$columnName]) != 'default') {
               $this->fillContent($valueCell, $count, $predKey, $content,$columnName);
               return "<td valign='top' rowspan=\"$count\">$valueCell </td>";

              } else {
                 $this->fillContent($valueCell, $count, $predKey, $content,$columnName);
               return "<td valign='top' rowspan=\"$count\">$valueCell </td>";

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
    private function fillContent($cell, $count, $previousValue, $content, $columnName)
    {

        $contentArray["count"] = $count;
        $contentArray["previousValue"] = $previousValue;
        foreach ($content as $key => $value) {
            $contentArray[$key] = $value;
        }
        $tmpArray[$columnName ]=[$cell => $contentArray];
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