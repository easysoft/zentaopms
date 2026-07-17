#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->saveTmpGanttVersion();
cid=0

- 保存临时甘特版本：验证count字段 @5
- 保存临时甘特版本：验证最旧版本被删除 @2
- 保存临时甘特版本：验证新记录的type字段 @taged
- 保存临时甘特版本：验证新记录的status字段 @tmpGantt
- 保存临时甘特版本：验证新记录的category字段 @gantt
- 保存临时甘特版本：验证新记录的project字段 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$project = zenData('project');
$project->id->range('1');
$project->type->range('project');
$project->name->range('项目1');
$project->status->range('wait');
$project->deleted->range('0');
$project->gen(1);

$object = zenData('object');
$object->id->range('1-5');
$object->project->range('1{5}');
$object->type->range('taged{5}');
$object->status->range('tmpGantt{5}');
$object->category->range('gantt{5}');
$object->version->range('v1,v2,v3,v4,v5');
$object->title->range('版本1,版本2,版本3,版本4,版本5');
$object->data->range('data1,data2,data3,data4,data5');
$object->gen(5);

$programplan = new programplanModelTest();

$result = $programplan->saveTmpGanttVersionTest(1, 'gantt', 'test');
r($result) && p('count')        && e('5');       // 保存临时甘特版本：验证count字段
r($result) && p('0:id')         && e('2');       // 保存临时甘特版本：验证最旧版本被删除
r($result) && p('4:type')       && e('taged');   // 保存临时甘特版本：验证新记录的type字段
r($result) && p('4:status')     && e('tmpGantt');// 保存临时甘特版本：验证新记录的status字段
r($result) && p('4:category')   && e('gantt');   // 保存临时甘特版本：验证新记录的category字段
r($result) && p('4:project')    && e('1');       // 保存临时甘特版本：验证新记录的project字段
