#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignUsersForBatchEdit();
timeout=0
cid=0

- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'normal', 'product'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'normal', 'execution'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'single_project', 'project'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'empty', 'product'  @5
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'multi_branch', 'execution'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-3');
$bug->project->range('1-3');
$bug->execution->range('101-103');
$bug->gen(10);

$user = zenData('user');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0{5}');
$user->gen(5);

su('admin');

$bugTest = new bugTest();

r($bugTest->assignUsersForBatchEditTest('normal', 'product')) && p() && e('1');
r($bugTest->assignUsersForBatchEditTest('normal', 'execution')) && p() && e('1');
r($bugTest->assignUsersForBatchEditTest('single_project', 'project')) && p() && e('1');
r($bugTest->assignUsersForBatchEditTest('empty', 'product')) && p() && e('5');
r($bugTest->assignUsersForBatchEditTest('multi_branch', 'execution')) && p() && e('1');