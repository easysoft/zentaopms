#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getPairs();
cid=1
pid=1

正常查询 >> 1
productID为空测试 >> 10,9
productID不存在测试 >> 0

*/
$productID = array('1', '', '1000');

$testreport = new testreportTest();
r($testreport->getPairsTest($productID[0])[0]) && p()       && e('1');    //正常查询
r($testreport->getPairsTest($productID[1]))    && p('0,1')  && e('10,9'); //productID为空测试
r($testreport->getPairsTest($productID[2]))    && p()       && e('0');    //productID不存在测试