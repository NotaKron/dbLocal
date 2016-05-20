<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractFile
 *
 * @author trkadmin
 */
abstract class FileAbstract {
    protected $_fileName;
    protected $_textFile;
    protected $_errorString;
    
       public function getFileName() {  //Возвращает имя файла, переданного в конструкторе
         
        return $this->_fileName;
    }
    public function getFileText(){
        return $this->_textFile;
    }
    public function getErrorText() {
      return $this->_errorString;  
    }
    
    public function openFile()
    {// Пытается открыть файл и считывает его значение. Иначе пишет ошибку
        try {
            $fp=  fopen($this->_fileName,'r');
            $sizeFile=  filesize($this->_fileName);
              $this->_textFile= fread($fp, $sizeFile);
              fclose($fp);
              return $this->_textFile;
        } catch (Exception $ex) {
            $this->_errorString=$ex->getMessage();
            return $this->_errorString;
            
        }
    }
    public function parseFile($pattern,$text)
     {
        if($text!=null)
    {   
      if (preg_match($pattern,$text, $arr))
              return $arr;      
     }
     }
     //put your code here
}
