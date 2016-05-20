<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConfigFile
 *
 * @author trkadmin
 */
class ConfigFile extends FileAbstract{
    private $_aliasMap;
    private $_sourceMap;
            function __construct($fileName) {
        $this->_fileName=$fileName;
    }
    public function getSource(){
     $file = $this->parseFile('|<Source>(.*)</Source>|sei', $this->_textFile);
               $this->_sourceMap= $this->splitText($file[1]);
     return $this->_sourceMap;
    }
    public function getAliasMap()
    {
        $this->_aliasMap=NULL;
     $file=  $this->parseFile('#<Alias>(.*)</Alias>#sei', $this->_textFile);
          $this->_aliasMap= $this->splitText($file[1]);
     return $this->_aliasMap;
    }
    private function splitText($_text)
    {
      $resultMap=array();
        $_arrayString=  explode("\r\n", $_text);
      foreach ($_arrayString as $value)
      {
         if((isset($value))and (!empty($value)))
             { 
        $_splitText= explode(":=", $value); 
        for($count=0;$count<count($_splitText); ++$count)
         {
           $_key=$_splitText[$count];
           $count++;
           $_value=$_splitText[$count];
           $resultMap[$_key]=$_value;
         }
         }
     }
     return $resultMap;
    }
    function getFilesName() {
        $order = 0;
        $aliasMap=$this->getAliasMap();
    $fdir = array();
    foreach ($aliasMap as  $key=>$value){
        $path='./classes/config/'.$key;
        echo $path.'<br>';
    if (false !== ($files = scandir($path, $order))) {
        foreach ($files as $file_name) {
            if ($file_name != '.' && $file_name != '..' ) {
               $_tmp=iconv('Windows-1251','UTF-8', $file_name);
               $fdir[] = pathinfo($_tmp,PATHINFO_FILENAME);
          }
        }
    }
}
    return ($fdir);
}
   




//put your code here
}        
           
           
                     
                    
                
  
