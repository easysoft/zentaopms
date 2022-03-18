#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$productplan = new Productplan('admin');

$List = array();
$List[0] = 0;

r($productplan->getList(1, 0, 'all', null, 'begin_desc', 'kipparent')) && p('') && e('0'); //返回空数组
?>
