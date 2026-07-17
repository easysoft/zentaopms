#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->rollbackTaskRelation();
cid=0

- 正常回滚关系：验证result字段 @1
- 正常回滚关系：验证count字段 @2
- 正常回滚关系：验证第一条关系的execution字段 @2,1
- 正常回滚关系：验证第一条关系的pretask字段 @10
- 正常回滚关系：验证第一条关系的task字段 @20
- 正常回滚关系：验证第一条关系的condition字段 @end
- 正常回滚关系：验证第一条关系的action字段 @begin
- 正常回滚关系：验证第二条关系的condition字段 @begin
- 正常回滚关系：验证第二条关系的action字段 @begin
- 正常回滚关系：验证第二条关系的pretask字段 @30
- 正常回滚关系：验证第二条关系的task字段 @40

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$relation = zenData('relationoftasks');
$relation->id->range('1-3');
$relation->project->range('1{3}');
$relation->execution->range('1,2,3');
$relation->pretask->range('10,20,30');
$relation->condition->range('end{3}');
$relation->task->range('100,200,300');
$relation->action->range('begin{3}');
$relation->gen(3);

$programplan = new programplanModelTest();

$relation1 = new stdclass();
$relation1->id     = 1;
$relation1->source = '1-10';
$relation1->target = '2-20';
$relation1->type   = 0;

$relation2 = new stdclass();
$relation2->id     = 2;
$relation2->source = '3-30';
$relation2->target = '4-40';
$relation2->type   = 1;

$result = $programplan->rollbackTaskRelationTest(1, array($relation1, $relation2));
r($result) && p('result')           && e('1');     // 正常回滚关系：验证result字段
r($result) && p('count')            && e('2');     // 正常回滚关系：验证count字段
r($result) && p('0:execution', ';') && e('2,1');   // 正常回滚关系：验证第一条关系的execution字段
r($result) && p('0:pretask')        && e('10');    // 正常回滚关系：验证第一条关系的pretask字段
r($result) && p('0:task')           && e('20');    // 正常回滚关系：验证第一条关系的task字段
r($result) && p('0:condition')      && e('end');   // 正常回滚关系：验证第一条关系的condition字段
r($result) && p('0:action')         && e('begin'); // 正常回滚关系：验证第一条关系的action字段
r($result) && p('1:condition')      && e('begin'); // 正常回滚关系：验证第二条关系的condition字段
r($result) && p('1:action')         && e('begin'); // 正常回滚关系：验证第二条关系的action字段
r($result) && p('1:pretask')        && e('30');    // 正常回滚关系：验证第二条关系的pretask字段
r($result) && p('1:task')           && e('40');    // 正常回滚关系：验证第二条关系的task字段
