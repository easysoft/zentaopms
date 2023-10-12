#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->config('action')->gen(8);
zdTable('product')->config('product')->gen(2);
zdTable('project')->config('execution')->gen(8);


/**

title=测试 actionModel->getRepeatObjectTest();
cid=1
pid=1

测试actionID为1，table为zt_product的情况 >> 2
测试actionID为3，table为zt_product的情况 >> 2
测试actionID为5，table为zt_product的情况 >> 4
测试actionID为7，table为zt_product的情况 >> 6

*/

$action = new actionTest();

$actionIDList = array(1, 3, 5, 7);

$tableList = array('zt_project', 'zt_product');

r($result = $action->getRepeatObjectTest($actionIDList[0], $tableList[1])) && p('id') && e('2');  //测试actionID为1，table为zt_product的情况
r($result = $action->getRepeatObjectTest($actionIDList[1], $tableList[0])) && p('id') && e('2');  //测试actionID为3，table为zt_product的情况
r($result = $action->getRepeatObjectTest($actionIDList[2], $tableList[0])) && p('id') && e('4');  //测试actionID为5，table为zt_product的情况
r($result = $action->getRepeatObjectTest($actionIDList[3], $tableList[0])) && p('id') && e('6');  //测试actionID为7，table为zt_product的情况


