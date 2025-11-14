#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->loadYaml('action_year')->gen(35);
zenData('actionrecent')->loadYaml('action_year')->gen(35);
zenData('actionproduct')->loadYaml('actionproduct')->gen(35);
zenData('doclib')->loadYaml('doclib')->gen(15);
zenData('doc')->loadYaml('doc')->gen(5);
zenData('product')->gen(5);
zenData('project')->loadYaml('execution')->gen(12);
zenData('user')->loadYaml('user')->gen(3);
zenData('userview')->loadYaml('userview')->gen(2);

/**

title=测试 actionModel->getDynamicByProduct();
timeout=0
cid=14896

- 查找所有用户动态 @7
- 查找用户admin动态 @3
- 查找用户dev17动态 @2
- 查找用户test18动态 @2
- 查找今天的动态 @1
- 查找昨天的动态 @0
- 查找今天的动态 @2

*/

$productID = '1';
$accountList = array('all', 'admin', 'dev17', 'test18');
$typeList    = array('all', 'today', 'yesterday', 'lastweek');
$dateList    = array('', 'today');

$actionTest = new actionTest();

su('admin');
r($actionTest->getDynamicByProductTest($productID, $accountList[0])) && p() && e('7');  // 查找所有用户动态
r($actionTest->getDynamicByProductTest($productID, $accountList[1])) && p() && e('3');  // 查找用户admin动态
r($actionTest->getDynamicByProductTest($productID, $accountList[2])) && p() && e('2');  // 查找用户dev17动态
r($actionTest->getDynamicByProductTest($productID, $accountList[3])) && p() && e('2');  // 查找用户test18动态

$actionTest->objectModel->dao->update(TABLE_ACTIONPRODUCT)->set('product')->eq('1')->where('action')->eq('33')->exec();
r($actionTest->getDynamicByProductTest($productID, $accountList[3], $typeList[1])) && p() && e('1');   // 查找今天的动态
r($actionTest->getDynamicByProductTest($productID, $accountList[1], $typeList[2])) && p() && e('0');   // 查找昨天的动态

r($actionTest->getDynamicByProductTest($productID, $accountList[0], $typeList[1])) && p() && e('2');  // 查找今天的动态
