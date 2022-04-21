#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('po1');

/**

title=测试 reportModel->getUserYearProducts();
cid=1
pid=1

测试获取本年度 po1 的产品数 >> 1,11,21,31,41,51,61,71,81,91,101,111
测试获取本年度 dev17 的产品数 >> 17,117
测试获取本年度 test18 的产品数 >> 18,118
测试获取本年度 po1 dev17 的产品数 >> 1,11,17,21,31,41,51,61,71,81,91,101,111,117
测试获取本年度 po1 test18 的产品数 >> 1,11,18,21,31,41,51,61,71,81,91,101,111,118

*/
$account = array('po1', 'dev17', 'test18', 'po1,dev17', 'po1,test18');

$report = new reportTest();

r($report->getUserYearProductsTest($account[0])) && p() && e('1,11,21,31,41,51,61,71,81,91,101,111');        // 测试获取本年度 po1 的产品数
r($report->getUserYearProductsTest($account[1])) && p() && e('17,117');                                      // 测试获取本年度 dev17 的产品数
r($report->getUserYearProductsTest($account[2])) && p() && e('18,118');                                      // 测试获取本年度 test18 的产品数
r($report->getUserYearProductsTest($account[3])) && p() && e('1,11,17,21,31,41,51,61,71,81,91,101,111,117'); // 测试获取本年度 po1 dev17 的产品数
r($report->getUserYearProductsTest($account[4])) && p() && e('1,11,18,21,31,41,51,61,71,81,91,101,111,118'); // 测试获取本年度 po1 test18 的产品数