#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$team = zdTable('team');
$team->id->range('2-9');
$team->root->range('2,7-9{3}');
$team->type->range('project,execution{7}');
$team->account->range('admin,user1,user2,admin');
$team->limited->range('no');
$team->join->range('`2023-04-02`');
$team->days->range('7');
$team->hours->range('7');
$team->gen(8);

$execution = zdTable('project');
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
cid=1

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

r($diffProjectMembers['admin'])   && p() && e('admin');
r($diffExecutionMembers['admin']) && p() && e('admin');
