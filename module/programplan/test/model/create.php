#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->create();
timeout=0
cid=17738

- 传入空数据第message条的0属性 @『阶段名称』不能为空。
- 分解任务第0条的attribute属性 @request
- 测试正常更新阶段信息 获取阶段数量 @14
- 测试正常更新阶段信息 检查数据信息
 - 第0条的name属性 @阶段31
 - 第1条的name属性 @阶段121
- 测试正常更新阶段信息 获取阶段数量 @4
- 测试正常更新阶段信息 获取数据信息
 - 第0条的name属性 @新阶段31
 - 第1条的name属性 @新阶段121
 - 第3条的name属性 @新增的阶段

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$project = zenData('project');
$project->type->range('project');
$project->gen(10);
zenData('task')->gen(10);

$names    = array('新阶段31', '新阶段121', '阶段211', '新增的阶段');
$parent    = array('2', '2', '2', '2');
$begin    = array();
$end      = array();
$idList   = array(11, 12);
$create   = array('name' => $names, 'parent' => $parent, 'begin' => $begin, 'end' => $end, 'id' => $idList);

$programplan = new programplanModelTest();

$programplan->objectModel->create(array());
r(dao::getError()) && p('message:0') && e('『阶段名称』不能为空。'); // 传入空数据

r($programplan->createTest(array(), 0, 0, 101)) && p('0:attribute') && e('request'); // 分解任务

$plans1 = $programplan->createTest();
r(count($plans1)) && p()                && e('14');             // 测试正常更新阶段信息 获取阶段数量
r($plans1)        && p('0:name;1:name') && e('阶段31,阶段121'); // 测试正常更新阶段信息 检查数据信息

$plans2 = $programplan->createTest($create);
r(count($plans2)) && p()                        && e('4');                             // 测试正常更新阶段信息 获取阶段数量
r($plans2)        && p('0:name;1:name;3:name') && e('新阶段31,新阶段121,新增的阶段'); // 测试正常更新阶段信息 获取数据信息