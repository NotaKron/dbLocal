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
abstract class AbstractFile {
    protected $_fileName;
    protected $_textFile;
    protected $_errorString;
    protected $_fileArray;
    public function GetFileName() {
        return $this->_fileName;
    }
    public function GetFileText(){
        return $this->_textFile;
    }
    public function GetErrorText() {
      return $this->_errorString;  
    }
    public function OpenFile()
    {
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
    public function ParseFile($pattern,$text)
     {
        if($text!=null)
    {   
      if (preg_match($pattern,$text, $arr))
              return $arr;
           
     }
     }
     public function GetTextArray()
     {
         $this->_fileArray=  file($this->_fileName);
         return $this->_fileArray;
     }






     //put your code here
}
