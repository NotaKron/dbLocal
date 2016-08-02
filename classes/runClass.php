<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of runClass
 *
 * @author trkadmin
 */

require './DbWork/MsSQL.classes.php';
$con = new MsSQL('Password=sa;Persist Security Info=True;User ID=sa;Initial Catalog=RK7_SQL;Data Source=DESKTOP-QS8D9HD\SQLNOUT;');
$query='SELECT
UNCHANGEABLEORDERTYPES00.NAME AS "ORDER_CATEGORY",
CURRENCIES00.NAME AS "CURRENCY",
CLASSIFICATORGROUPS0001.NAME AS "Cash",
PayBindings.QUANTITY AS QUANTITY,
PayBindings.PAYSUM AS PAYSUM

FROM PAYBINDINGS
LEFT JOIN SaleObjects SaleObjects00
  ON (SaleObjects00.Visit = PayBindings.Visit) AND (SaleObjects00.MidServer = PayBindings.MidServer) AND (SaleObjects00.DishUNI = PayBindings.DishUNI) AND (SaleObjects00.ChargeUNI = PayBindings.ChargeUNI)
LEFT JOIN OrderSessions OrderSessions00
  ON (OrderSessions00.Visit = SaleObjects00.Visit) AND (OrderSessions00.MidServer = SaleObjects00.MidServer) AND (OrderSessions00.UNI = SaleObjects00.SessionUNI)
LEFT JOIN Orders Orders00
  ON (Orders00.Visit = OrderSessions00.Visit) AND (Orders00.MidServer = OrderSessions00.MidServer) AND (Orders00.IdentInVisit = OrderSessions00.OrderIdent)
JOIN GLOBALSHIFTS GLOBALSHIFTS00
ON (GLOBALSHIFTS00.SHIFTNUM=Orders00.iCommonShift)
LEFT JOIN UNCHANGEABLEORDERTYPES UNCHANGEABLEORDERTYPES00
  ON (UNCHANGEABLEORDERTYPES00.SIFR = Orders00.UOT)
  JOIN CurrLines CurrLines00
  ON (CurrLines00.Visit = PayBindings.Visit) AND (CurrLines00.MidServer = PayBindings.MidServer) AND (CurrLines00.UNI = PayBindings.CurrUNI)
  LEFT JOIN CURRENCIES CURRENCIES00
  ON (CURRENCIES00.SIFR = CurrLines00.Sifr)
LEFT JOIN SessionDishes SessionDishes00
  ON (SessionDishes00.Visit = SaleObjects00.Visit) AND (SessionDishes00.MidServer = SaleObjects00.MidServer) AND (SessionDishes00.UNI = SaleObjects00.DishUNI)  
LEFT JOIN MENUITEMS MENUITEMS00
  ON (MENUITEMS00.SIFR = SessionDishes00.Sifr)
LEFT JOIN DISHGROUPS DISHGROUPS0001
  ON (DISHGROUPS0001.CHILD = MENUITEMS00.SIFR) AND (DISHGROUPS0001.CLASSIFICATION = 2560)
LEFT JOIN CLASSIFICATORGROUPS CLASSIFICATORGROUPS0001
  ON CLASSIFICATORGROUPS0001.SIFR * 256 + CLASSIFICATORGROUPS0001.NumInGroup = DISHGROUPS0001.PARENT 
  JOIN PrintChecks PrintChecks00
  ON (PrintChecks00.Visit = CurrLines00.Visit) AND (PrintChecks00.MidServer = CurrLines00.MidServer) AND (PrintChecks00.UNI = CurrLines00.CheckUNI)
  WHERE
  ((PrintChecks00.STATE = 6) AND(GLOBALSHIFTS00.SHIFTDATE BETWEEN \'20101215\' AND \'20101218\' ) )
  ORDER BY  ORDER_CATEGORY,CURRENCY,Cash';
if ($con!=NULL){
$res=$con->getResult($query);
}
require './Modules/modules.php';
require './Modules/tableArray.php';
//$modul= new Modules($res,$query);
$modul= new tableArray($res,$query);
$modul->test();