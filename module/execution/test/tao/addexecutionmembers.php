#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

zdTable('team')->gen(0);
zdTable('user')->gen(4);
$project = zdTable('project');
$project->project->range('0,1');
$project->type->range('project,sprint');
$project->gen(2);

/**

title=productModel->addExecutionMembers();
cid=1
pid=1

*/

$execution = new executionTest('admin');
$members   = array('admin', 'user1', 'user2');

r(count($execution->addExecutionMembersTest(0, array())))   && p() && e('0'); //空的执行ID，空的团队
r(count($execution->addExecutionMembersTest(0, $members)))  && p() && e('0'); //空的执行ID，有团队
r(count($execution->addExecutionMembersTest(5, $members)))  && p() && e('0'); //不存在的执行ID，有团队
r(count($execution->addExecutionMembersTest(2, array())))   && p() && e('0'); //存在的执行ID，空团队
r(count($execution->addExecutionMembersTest(2, $members)))  && p() && e('3'); //存在的执行ID，有团队
