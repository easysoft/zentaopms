#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$productplan = new Productplan('admin');

$IDlist = array();
$IDlist[0] = 1;
$IDlist[1] = 1000;
$IDlist[2] = 3;

//var_dump($productplan->getByIDList($IDlist));die;

r($productplan->getByIDList($IDlist))    && p('3:id') && e('3'); //传入一个数组，取值id为3的数据
r($productplan->getByIDList($IDlist))    && p('1:id') && e('1'); //传入一个数组，取值id为1的数据
r($productplan->getByIDList($IDlist[0])) && p('1:id') && e('1'); //传入一个数值，正常取值
r($productplan->getByIDList($IDlist[1])) && p()       && e('0'); //传入一个不存在的值返回布尔值
?>
