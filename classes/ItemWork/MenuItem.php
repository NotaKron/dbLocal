<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuItem
 *
 * @author trkadmin
 */
class MenuItem {
        function __autoload($className)
        {
        include './FileWork/'.$className.'.php';
    }
    public function createMenuItem($itemArray)
            {
        echo '<div class=" side">';
        echo '<ul class=" menu">';
        echo '<li class=" menu_list"><a href="#">Отчеты</a>';
        echo '<ul class=" menu_drop">';
        foreach ($itemArray as $item)
        {
            echo '<li><a href =#>'.$item.'</a></li>';
        }
    }
    //put your code here
}
