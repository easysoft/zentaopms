#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$team = zenData('team');
$team->id->range('2-9');
$team->root->range('2,7-9{3}');
$team->type->range('project,execution{7}');
$team->account->range('admin,user1,user2,admin');
$team->limited->range('no');
$team->join->range('`2023-04-02`');
$team->days->range('7');
$team->hours->range('7');
$team->gen(8);

$execution = zenData('project');
$execution->id->range('2,6,8,9');
$execution->project->range('2,3');
$execution->name->prefix('项目')->range('8,9');
$execution->code->prefix('project')->range('8,9');
$execution->type->range('project,sprint{2},kanban');
$execution->status->range('doing,suspended');
$execution->gen(4);

su('admin');

/**

title=测试 projectModel::unlinkmember;
timeout=0
cid=17871

- 查看项目成员是否删除 @admin
- 查看执行成员是否删除 @admin
- 查看移除后的项目成员 @0
- 查看移除后的项目成员属性user1 @用户1
- 查看移除后的项目成员数量 @0
- 查看移除后的项目成员数量 @2

*/

global $tester;
$tester->loadModel('project');

$beforeProjectMembers   = $tester->project->getTeamMemberPairs(2);
$beforeExecutionMembers = $tester->project->getTeamMemberPairs(8);

$tester->project->unlinkMember(2, 'admin', 'yes');

$afterProjectMembers   = $tester->project->getTeamMemberPairs(2);
$afterExecutionMembers = $tester->project->getTeamMemberPairs(8);
$diffProjectMembers    = array_diff($beforeProjectMembers, $afterProjectMembers);
$diffExecutionMembers  = array_diff($beforeExecutionMembers, $afterExecutionMembers);

r($diffProjectMembers['admin'])   && p()        && e('admin'); // 查看项目成员是否删除
r($diffExecutionMembers['admin']) && p()        && e('admin'); // 查看执行成员是否删除
r($afterProjectMembers)           && p()        && e('0');     // 查看移除后的项目成员
r($afterExecutionMembers)         && p('user1') && e('用户1'); // 查看移除后的项目成员
r(count($afterProjectMembers))    && p()        && e('0');     // 查看移除后的项目成员数量
r(count($afterExecutionMembers))  && p()        && e('2');     // 查看移除后的项目成员数量