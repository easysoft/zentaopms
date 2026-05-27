#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 testtaskModel->fetchTesttaskList();
timeout=0
cid=19222

- 处理product=0,paramID=0的查询语句 @( 1 = 1)
- 处理product=0,paramID=1的查询语句 @( 1 = 1)
- 处理product=1,paramID=0的查询语句 @( 1 = 1 AND t1.`product` ='1')
- 处理product=1,paramID=1的查询语句 @( 1 = 1 AND t1.`product` ='1')
- 处理product=2,paramID=0的查询语句 @( 1 = 1 AND t1.`product` ='2')
- 处理product=2,paramID=1的查询语句 @( 1 = 1 AND t1.`product` ='2')

*/
zenData('product')->gen(5);

$productID   = array(0, 1, 2);
$paramID   = array(0, 1);
$module = array('projectTesttask', 'executionTesttask');

global $tester;
$testtask = $tester->loadModel('testtask');
r($testtask->processSearchQuery($productID[0], $paramID[0], $module[0])) && p() && e("( 1 = 1)"); // 处理product=0,paramID=0的查询语句
r($testtask->processSearchQuery($productID[0], $paramID[1], $module[0])) && p() && e("( 1 = 1)"); // 处理product=0,paramID=1的查询语句
r($testtask->processSearchQuery($productID[1], $paramID[0], $module[0])) && p() && e("( 1 = 1 AND t1.`product` ='1')"); //处理product=1,paramID=0的查询语句
r($testtask->processSearchQuery($productID[1], $paramID[1], $module[1])) && p() && e("( 1 = 1 AND t1.`product` ='1')");//处理product=1,paramID=1的查询语句
r($testtask->processSearchQuery($productID[2], $paramID[0], $module[1])) && p() && e("( 1 = 1 AND t1.`product` ='2')");//处理product=2,paramID=0的查询语句
r($testtask->processSearchQuery($productID[2], $paramID[1], $module[1])) && p() && e("( 1 = 1 AND t1.`product` ='2')"); // 处理product=2,paramID=1的查询语句