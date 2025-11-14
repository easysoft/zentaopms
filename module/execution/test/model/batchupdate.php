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
cid=16267

- 测试批量修改任务
 - 第0条的field属性 @name
 - 第0条的old属性 @迭代1
 - 第0条的new属性 @批量修改执行一
- 测试批量修改任务
 - 第1条的field属性 @PM
 - 第1条的old属性 @~~
 - 第1条的new属性 @outside100
- 测试批量修改任务
 - 第4条的field属性 @days
 - 第4条的old属性 @0
 - 第4条的new属性 @5
- 测试批量修改任务
 - 第5条的field属性 @code
 - 第5条的old属性 @program3
 - 第5条的new属性 @批量修改执行一code
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
r($result)                                               && p('0:field,old,new') && e('name,迭代1,批量修改执行一');         // 测试批量修改任务
r($result)                                               && p('1:field,old,new') && e('PM,~~,outside100');                  // 测试批量修改任务
r($result)                                               && p('4:field,old,new') && e('days,0,5');                          // 测试批量修改任务
r($result)                                               && p('5:field,old,new') && e('code,program3,批量修改执行一code');  // 测试批量修改任务
r($execution->batchUpdateObject($noName, $executionID))  && p('name:0')          && e('~f:名称』不能为空。$~');             // 测试name为空
