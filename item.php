<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <title>Документ без названия</title>
 <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <?php
include './classes/FileWork/FileAbstract.php';
    include './classes/FileWork/ConfigFile.php';
  $configFile= new ConfigFile('./classes/config/config.ini');
  $configFile->openFile();
  $itemsArray=$configFile->getFilesName();
  print_r($itemsArray);
  include './classes/ItemWork/MenuItem.php';
  $items=new MenuItem();
  $items->createMenuItem($itemsArray);
    ?>
    </body>
</html>

