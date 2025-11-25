#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getAssignedToPairs();
timeout=0
cid=15446

- 执行bugTest模块的getAssignedToPairsTest方法，参数是$bug1  @3
- 执行bugTest模块的getAssignedToPairsTest方法，参数是$bug2  @2
- 执行bugTest模块的getAssignedToPairsTest方法，参数是$bug3  @4
- 执行bugTest模块的getAssignedToPairsTest方法，参数是$bug4  @10
- 执行$users['closed']) ? 1 : 0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备user测试数据
$user = zendata('user');
$user->id->range('1-10');
$user->account->range('user1,user2,user3,user4,user5,user6,user7,user8,user9,user10');
$user->realname->range('用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9,用户10');
$user->deleted->range('0');
$user->gen(10);

// 准备team测试数据
$team = zendata('team');
$team->id->range('1-12');
$team->root->range('1,1,1,2,2,2,1,1,2,2,3,3');
$team->type->range('execution,execution,execution,execution,execution,execution,project,project,project,project,project,project');
$team->account->range('user1,user2,user3,user4,user5,user6,user1,user2,user3,user4,user7,user8');
$team->gen(12);

// 准备bug测试数据
$bug = zendata('bug');
$bug->id->range('1-10');
$bug->product->range('1,1,1,1,2,2,2,3,3,3');
$bug->project->range('0,1,1,0,0,2,0,0,3,0');
$bug->execution->range('1,0,2,0,0,0,0,0,0,0');
$bug->branch->range('0');
$bug->assignedTo->range('user1,user2,user3,user4,user5,user6,user7,user8,user9,closed');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10');
$bug->status->range('active,active,active,active,active,active,active,active,active,closed');
$bug->deleted->range('0');
$bug->gen(10);

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 测试1:bug有execution=1时,返回execution的团队成员(user1,user2,user3)
$bug1 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(1)->fetch();
r(count($bugTest->getAssignedToPairsTest($bug1))) && p() && e('3');

// 测试2:bug有project=1(无execution)时,返回project的团队成员(user1,user2)
$bug2 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(2)->fetch();
r(count($bugTest->getAssignedToPairsTest($bug2))) && p() && e('2');

// 测试3:bug有execution=2时,返回execution的团队成员(user4,user5,user6)+assignedTo(user3)=4
$bug3 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(3)->fetch();
r(count($bugTest->getAssignedToPairsTest($bug3))) && p() && e('4');

// 测试4:bug只有product=1(无execution和project)时,返回所有用户
$bug4 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(4)->fetch();
r(count($bugTest->getAssignedToPairsTest($bug4))) && p() && e('10');

// 测试5:bug的assignedTo=closed时,不追加到列表
$bug10 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(10)->fetch();
$users = $bugTest->getAssignedToPairsTest($bug10);
r(isset($users['closed']) ? 1 : 0) && p() && e('0');