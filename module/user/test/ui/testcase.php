#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/testcase.ui.class.php';

/**

title=开源版user模块视图层testcase显示测试
timeout=0
cid=1

- 用户用例页面'指派给...'成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @用户用例页面'指派给...'测试成功
- 用户用例页面'由...创建'成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @用户用例页面'由...创建'测试成功

*/

zenData('company')->gen(1);

$users = array(
    (object)array('id' => 1, 'account' => 'admin', 'realname' => '管理员', 'gender' => 'f', 'role' => 'admin'),
    (object)array('id' => 2, 'account' => 'user1', 'realname' => '用户1', 'gender' => 'm', 'role' => 'dev'),
    (object)array('id' => 3, 'account' => 'user2', 'realname' => '用户2', 'gender' => 'm', 'role' => 'dev'),
    (object)array('id' => 4, 'account' => 'user3', 'realname' => '用户3', 'gender' => 'm', 'role' => 'dev'),
);

$openedDate  = '2023-01-01 00:00:00';
$lastRunDate = '2023-06-01 00:00:00';

$testcases = array(
    (object)array(
        'id'            => 1,
        'userID'        => 1,
        'title'         => '测试用例1',
        'type'          => 'feature',
        'stage'         => 'unittest',
        'pri'           => 1,
        'status'        => 'normal',
        'openedBy'      => $users[0]->account,
        'openedDate'    => $openedDate,
        'assignedTo'    => $users[1]->account,
        'lastRunner'    => $users[0]->realname,
        'lastRunDate'   => $lastRunDate,
        'lastRunResult' => 'pass'
    ),
    (object)array(
        'id'            => 2,
        'userID'        => 2,
        'title'         => '测试用例2',
        'type'          => 'feature',
        'stage'         => 'unittest',
        'pri'           => 2,
        'status'        => 'normal',
        'openedBy'      => $users[1]->account,
        'openedDate'    => $openedDate,
        'assignedTo'    => $users[2]->account,
        'lastRunner'    => $users[1]->realname,
        'lastRunDate'   => $lastRunDate,
        'lastRunResult' => 'pass'
    ),
    (object)array(
        'id'            => 3,
        'userID'        => 3,
        'title'         => '测试用例3',
        'type'          => 'feature',
        'stage'         => 'unittest',
        'pri'           => 3,
        'status'        => 'blocked',
        'openedBy'      => $users[2]->account,
        'openedDate'    => $openedDate,
        'assignedTo'    => $users[3]->account,
        'lastRunner'    => $users[2]->realname,
        'lastRunDate'   => $lastRunDate,
        'lastRunResult' => 'fail'
    ),
    (object)array(
        'id'            => 4,
        'userID'        => 4,
        'title'         => '测试用例4',
        'type'          => 'feature',
        'stage'         => 'unittest',
        'pri'           => 4,
        'status'        => 'investigate',
        'openedBy'      => $users[3]->account,
        'openedDate'    => $openedDate,
        'assignedTo'    => $users[0]->account,
        'lastRunner'    => $users[3]->realname,
        'lastRunDate'   => $lastRunDate,
        'lastRunResult' => 'blocked'
    ),
);

// Use above predefined data to generate db records
$user = zenData('user');
$user->id->range(implode(',', array_column($users, 'id')));
$user->account->range(implode(',', array_column($users, 'account')));
$user->realname->range(implode(',', array_column($users, 'realname')));
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range(implode(',', array_column($users, 'role')));
$user->gender->range(implode(',', array_column($users, 'gender')));
$user->gen(4);

$case = zenData('case');
$case->id->range(implode(',', array_column($testcases, 'id')));
$case->product->range('1');
$case->module->range('1');
$case->title->range(implode(',', array_column($testcases, 'title')));
$case->type->range('feature');
$case->stage->range('unittest');
$case->pri->range(implode(',', array_column($testcases, 'pri')));
$case->status->range(implode(',', array_column($testcases, 'status')));
$case->openedBy->range(implode(',', array_column($testcases, 'openedBy')));
$case->lastRunner->range(implode(',', array_column($testcases, 'lastRunner')));
$case->lastRunResult->range(implode(',', array_column($testcases, 'lastRunResult')));
$case->gen(4);

$testtask = zenData('testtask');
$testtask->status->range('doing');
$testtask->gen(4);

$testrun = zenData('testrun');
$testrun->task->range(implode(',', array_column($testcases, 'id')));
$testrun->case->range(implode(',', array_column($testcases, 'id')));
$testrun->assignedTo->range(implode(',', array_column($testcases, 'assignedTo')));
$testrun->lastRunResult->range(implode(',', array_column($testcases, 'lastRunResult')));
$testrun->gen(4);

$tester = new testcaseTester();

r($tester->verifyUserTestCases($users, $testcases, 'ToUser')) && p('status,message') && e("SUCCESS,用户用例页面'指派给...'测试成功"); //用户用例页面'指派给...'成功
r($tester->verifyUserTestCases($users, $testcases, 'ByUser')) && p('status,message') && e("SUCCESS,用户用例页面'由...创建'测试成功"); //用户用例页面'由...创建'成功

$tester->closeBrowser();