#!/usr/bin/env php
<?php

/**

title=测试 docModel::getModulesOfLibs();
timeout=0
cid=16110

- 执行docTest模块的getModulesOfLibsTest方法，参数是array  @10
- 执行docTest模块的getModulesOfLibsTest方法，参数是array 第1条的type属性 @doc
- 执行docTest模块的getModulesOfLibsTest方法，参数是array  @0
- 执行docTest模块的getModulesOfLibsTest方法，参数是array  @0
- 执行docTest模块的getModulesOfLibsTest方法，参数是array  @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$moduleTable = zenData('module');
$moduleTable->id->range('1-20');
$moduleTable->root->range('1{5},2{5},3{5},4{3},5{2}');
$moduleTable->branch->range('0');
$moduleTable->name->range('产品文档{3},接口文档{3},项目文档{4},用户文档{2},技术文档{3},设计文档{2},测试文档{3}');
$moduleTable->parent->range('0{10},1{3},2{3},3{2},4{2}');
$moduleTable->path->range(',1,{5},,2,{5},,3,{3},,1,2,{3},,1,3,{2},,2,3,{2}');
$moduleTable->grade->range('1{10},2{8},3{2}');
$moduleTable->order->range('1-20');
$moduleTable->type->range('doc{10},api{5},story{3},task{2}');
$moduleTable->from->range('0');
$moduleTable->owner->range('admin{5},user1{5},user2{5},user3{3},user4{2}');
$moduleTable->collector->range('');
$moduleTable->short->range('PD{2},AD{2},TD{1},""');
$moduleTable->deleted->range('0{18},1{2}');
$moduleTable->gen(20);

su('admin');

$docTest = new docTest();

r(count($docTest->getModulesOfLibsTest(array(1, 2), 'doc,api'))) && p() && e('10');
r($docTest->getModulesOfLibsTest(array(1), 'doc')) && p('1:type') && e('doc');
r(count($docTest->getModulesOfLibsTest(array(999)))) && p() && e('0');
r(count($docTest->getModulesOfLibsTest(array()))) && p() && e('0');
r(count($docTest->getModulesOfLibsTest(array(3), 'api'))) && p() && e('5');