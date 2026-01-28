#!/usr/bin/env php
<?php

/**

title=测试 docModel->getScopeItems();
timeout=0
cid=16124

- 获取产品范围
 - 第0条的value属性 @1
 - 第0条的text属性 @产品
- 获取项目范围
 - 第0条的value属性 @2
 - 第0条的text属性 @项目
- 获取执行范围
 - 第0条的value属性 @3
 - 第0条的text属性 @执行
- 获取个人范围
 - 第0条的value属性 @4
 - 第0条的text属性 @个人

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('user')->gen(5);
su('admin');

$scope = zenData('doclib');
$scope->id->range('1-10');
$scope->type->range('template');
$scope->vision->range('rnd{4},or{3},lite{2},rnd');
$scope->name->range('产品,项目,执行,个人,市场,项目,个人,产品,个人,自定义范围');
$scope->main->range('1{9},0');
$scope->gen(10);

$scopeList = array(array(1 => '产品'), array(2 => '项目'), array(3 => '执行'), array(4 => '个人'));

$docTester = new docModelTest();
r($docTester->getScopeItemsTest($scopeList[0])) && p('0:value,text') && e('1,产品'); // 获取产品范围
r($docTester->getScopeItemsTest($scopeList[1])) && p('0:value,text') && e('2,项目'); // 获取项目范围
r($docTester->getScopeItemsTest($scopeList[2])) && p('0:value,text') && e('3,执行'); // 获取执行范围
r($docTester->getScopeItemsTest($scopeList[3])) && p('0:value,text') && e('4,个人'); // 获取个人范围
