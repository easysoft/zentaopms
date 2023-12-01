#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('action')->gen(200);
zdTable('product')->gen(20);
zdTable('story')->gen(20);
zdTable('productplan')->gen(20);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearProducts();
cid=1
pid=1

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportTest();

r($report->getUserYearProductsTest($account[0])) && p() && e('1,2,3,4,5');                                          // 测试获取本年度 admin 的产品数
r($report->getUserYearProductsTest($account[1])) && p() && e('17');                                                 // 测试获取本年度 dev17 的产品数
r($report->getUserYearProductsTest($account[2])) && p() && e('1,18');                                               // 测试获取本年度 test18 的产品数
r($report->getUserYearProductsTest($account[3])) && p() && e('1,2,3,4,5,17');                                       // 测试获取本年度 admin dev17 的产品数
r($report->getUserYearProductsTest($account[4])) && p() && e('1,2,3,4,5,18');                                       // 测试获取本年度 admin test18 的产品数
r($report->getUserYearProductsTest($account[5])) && p() && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试获取本年度 所有用户 的产品数
