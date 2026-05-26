#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=测试 testtaskModel->fetchTesttaskList();
timeout=0
cid=19222

- 处理 bug 产品 1 分支 all 的查询语句 @( 1 = 1 AND t1.`product` ='1')
- 处理 bug 产品 2 分支 all 的查询语句 @( 1 = 1 AND t1.`product` ='1')
- 处理 bug 产品 1 分支 0   的查询语句 @( 1 = 1 AND t1.`product` ='1')
- 处理 story 产品 1 分支 all 的查询语句 @( 1 = 1 AND t1.`product` ='2')
- 处理 story 产品 2 分支 all 的查询语句 @( 1 = 1 AND t1.`product` ='2')
- 处理 story 产品 1 分支 0   的查询语句 @( 1 = 1 AND t1.`product` ='2')

*/
zenData('product')->gen(5);

$executionID = array(0,1);
$productID   = array(1, 2);

global $tester;
$tester->session->set('testtaskQuery', '`product` != \'0\'');

$testtask = $tester->loadModel('testtask');

r($testtask->processSearchQuery($executionID[0], $productID[0], 0)) && p() && e("( 1 = 1 AND t1.`product` ='1')");                          // 处理 bug 产品 1 分支 all 的查询语句
r($testtask->processSearchQuery($executionID[0], $productID[0], 0)) && p() && e("( 1 = 1 AND t1.`product` ='1')");                          // 处理 bug 产品 2 分支 all 的查询语句
r($testtask->processSearchQuery($executionID[0], $productID[0], 0)) && p() && e("( 1 = 1 AND t1.`product` ='1')"); // 处理 bug 产品 1 分支 0   的查询语句
r($testtask->processSearchQuery($executionID[1], $productID[1], 0)) && p() && e("( 1 = 1 AND t1.`product` ='2')");                          // 处理 story 产品 1 分支 all 的查询语句
r($testtask->processSearchQuery($executionID[1], $productID[1], 0)) && p() && e("( 1 = 1 AND t1.`product` ='2')");                          // 处理 story 产品 2 分支 all 的查询语句
r($testtask->processSearchQuery($executionID[1], $productID[1], 0)) && p() && e("( 1 = 1 AND t1.`product` ='2')"); // 处理 story 产品 1 分支 0   的查询语句

unset($_SESSION['testtaskQuery']);