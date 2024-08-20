#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/changestatus.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
$tester = new changeStatus();
$tester->login();

$productID['productID'] = 1;
r($tester->closeProduct($productID))  && p('message,status') && e('关闭产品成功,SUCCESS');

$tester->closeBrowser();
