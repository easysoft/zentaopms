#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('team')->gen(0);
zenData('user')->gen(4);
$project = zenData('project');
$project->project->range('0,1');
$project->type->range('project,sprint');
$project->gen(2);

/**

title=productModel->addExecutionMembers();
cid=16380
pid=1

- 空的执行ID，空的团队 @0
- 空的执行ID，有团队 @0
- 不存在的执行ID，有团队 @0
- 存在的执行ID，空团队 @0
- 存在的执行ID，有团队 @3

*/

$execution = new executionTest('admin');
$members   = array('admin', 'user1', 'user2');

r(count($execution->addExecutionMembersTest(0, array())))   && p() && e('0'); //空的执行ID，空的团队
r(count($execution->addExecutionMembersTest(0, $members)))  && p() && e('0'); //空的执行ID，有团队
r(count($execution->addExecutionMembersTest(5, $members)))  && p() && e('0'); //不存在的执行ID，有团队
r(count($execution->addExecutionMembersTest(2, array())))   && p() && e('0'); //存在的执行ID，空团队
r(count($execution->addExecutionMembersTest(2, $members)))  && p() && e('3'); //存在的执行ID，有团队
