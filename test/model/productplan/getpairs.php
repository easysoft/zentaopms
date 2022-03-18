#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$productplan = new Productplan('admin');

var_dump($productplan->getPairs($product = array(28, 29, 30), $branch = '1', $expired = '', $skipParent = false));die;
?>
