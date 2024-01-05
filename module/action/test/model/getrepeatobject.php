#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->config('action')->gen(10);
zdTable('product')->config('product')->gen(3);
zdTable('project')->config('execution')->gen(9);

/**

title=测试 actionModel->getRepeatObjectTest();
timeout=0
cid=1

- 测试actionID为1，table为zt_product的情况属性id @2
- 测试actionID为1，table为zt_product的情况属性id @1
- 测试actionID为3，table为zt_project的情况属性id @2
- 测试actionID为5，table为zt_project的情况属性id @4
- 测试actionID为5，table为zt_project的情况属性id @3
- 测试actionID为7，table为zt_project的情况属性id @3
- 测试actionID为8，table为zt_project的情况属性id @7

*/

$action = new actionTest();

$actionIDList = array(1, 2, 3, 5, 6, 7, 8);

$tableList = array('zt_project', 'zt_product');

r($result = $action->getRepeatObjectTest($actionIDList[0], $tableList[1])) && p('id') && e('2');  //测试actionID为1，table为zt_product的情况
r($result = $action->getRepeatObjectTest($actionIDList[1], $tableList[1])) && p('id') && e('1');  //测试actionID为1，table为zt_product的情况
r($result = $action->getRepeatObjectTest($actionIDList[2], $tableList[0])) && p('id') && e('2');  //测试actionID为3，table为zt_project的情况
r($result = $action->getRepeatObjectTest($actionIDList[3], $tableList[0])) && p('id') && e('4');  //测试actionID为5，table为zt_project的情况
r($result = $action->getRepeatObjectTest($actionIDList[4], $tableList[0])) && p('id') && e('3');  //测试actionID为5，table为zt_project的情况
r($result = $action->getRepeatObjectTest($actionIDList[5], $tableList[0])) && p('id') && e('3');  //测试actionID为7，table为zt_project的情况
r($result = $action->getRepeatObjectTest($actionIDList[6], $tableList[0])) && p('id') && e('7');  //测试actionID为8，table为zt_project的情况