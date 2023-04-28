#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
 su('admin');

function initData()
{
    $team = zdTable('team');
    $team->id->range('2-9');
    $team->root->range('2,7-9{3}');
    $team->type->range('project,execution{7}');
    $team->account->range('admin,user1,user2,admin');
    $team->limited->range('no');
    $team->join->range('2023-04-02');
    $team->days->range('7');
    $team->hours->range('7');
    $team->gen(8);

    $execution = zdTable('project');
    $execution->id->range('2,6,8,9');
    $execution->project->range('2,3');
    $execution->name->prefix('项目')->range('8,9');
    $execution->code->prefix('project')->range('8,9');
    $execution->type->range('project{2},sprint,kanban');
    $execution->status->range('doing,suspended,closed');
    $execution->gen(4);
}

/**

title=测试 projectModel::getByID;
timeout=0
cid=1
pid=1

- 执行$diffMembers @1 >> 1
 >> admin
- 执行$beforeMembers['admin'] @admin >> 0
 >> 1
- 执行$afterMembers['admin'] @0 >> admin
 >> 0

*/

global $tester;
$tester->loadModel('project');

initData();

$beforeMembers           = $tester->project->getTeamMemberPairs(2);
$beforeExecutionMembers  = $tester->project->getTeamMemberPairs(8);

$tester->project->unlinkMember(2, 'admin', 'yes');

$afterMembers           = $tester->project->getTeamMemberPairs(2);
$afterExecutionMembers  = $tester->project->getTeamMemberPairs(6);
$diffMembers            = count($beforeMembers) - count($afterMembers);
$diffExecutionMembers   = count($beforeExecutionMembers) - count($afterExecutionMembers);

r($diffMembers)                     && p() && e('1');
r($beforeMembers['admin'])          && p() && e('admin');
r($afterMembers['admin'])           && p() && e('0');
r($diffExecutionMembers)            && p() && e('1');
r($beforeExecutionMembers['admin']) && p() && e('admin');
r($afterExecutionMembers['admin'])  && p() && e('0');
