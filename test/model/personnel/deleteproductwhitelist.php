#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->deleteProductWhitelist();
cid=1
pid=1

删除了产品10的白名单，这里有个条件，source必须为sync，同步过来的才能删 >> 0

*/

$personnel = new personnelTest('admin');

$productID = array();
$productID[0] = 10;
$peoductID[1] = 11111;

$account = array();
$account[0] = 'admin';
$account[1] = 'test111';

$result1 = $personnel->deleteProductWhitelistTest($productID[0], $account[0]);

r($result1) && p() && e('0'); //删除了产品10的白名单，这里有个条件，source必须为sync，同步过来的才能删