#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->project->range('1');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->model->range('{1},scrum,{3}');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->gen(5);

/**

title=测试executionModel->batchUpdate();
timeout=0
cid=1

- 测试批量修改任务
 - 第0条的field属性 @name
 - 第0条的old属性 @迭代1
 - 第0条的new属性 @批量修改执行一
- 测试批量修改任务
 - 第1条的field属性 @PM
 - 第1条的old属性 @~~
 - 第1条的new属性 @outside100
- 测试批量修改任务
 - 第2条的field属性 @begin
 - 第2条的old属性 @2025-03-06
 - 第2条的new属性 @2025-06-04
- 测试批量修改任务
 - 第3条的field属性 @end
 - 第3条的old属性 @2025-07-11
 - 第3条的new属性 @2025-06-09
- 测试name为空第name条的0属性 @~f:名称』不能为空。$~

*/

$executionID = '3';
$name        = array($executionID => '批量修改执行一');
$code        = array($executionID => '批量修改执行一code');
$pms         = array($executionID => 'outside100');
$lifetimes   = array($executionID => 'short');
$statuses    = array($executionID => 'doing');
$days        = array($executionID => '5');

$normal  = array('name' => $name, 'status'=> $statuses, 'code' => $code, 'PM' => $pms, 'lifetime' => $lifetimes, 'days' => $days);
$noName  = array('status'=> $statuses, 'code' => $code, 'PM' => $pms, 'lifetime' => $lifetimes, 'days' => $days);

$execution = new executionTest();
$result = $execution->batchUpdateObject($normal, $executionID);
r($result)                                               && p('0:field,old,new') && e('name,迭代1,批量修改执行一');   // 测试批量修改任务
r($result)                                               && p('1:field,old,new') && e('PM,~~,outside100');            // 测试批量修改任务
r($result)                                               && p('2:field,old,new') && e('begin,2025-03-06,2025-06-04'); // 测试批量修改任务
r($result)                                               && p('3:field,old,new') && e('end,2025-07-11,2025-06-09');   // 测试批量修改任务
r($execution->batchUpdateObject($noName, $executionID))  && p('name:0')          && e('~f:名称』不能为空。$~');       // 测试name为空
