#!/usr/bin/env php
<?php

/**

title=测试 spaceZen::buildManageMembersData();
timeout=0
cid=0

- 测试传入空formData和空members并验证返回结果为空 @0
- 测试传入有效formData添加新成员并验证返回结果类型 @1
- 测试传入有效formData和现有members并验证返回结果类型 @1
- 测试传入空formData和有效members并验证返回结果为空 @0
- 测试传入有效数据并验证包含space键 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);
zenData('group')->gen(5);
zenData('usergroup')->gen(10);

/* Set app context for zen class */
global $tester;
$tester->app->rawModule  = 'space';
$tester->app->rawMethod  = 'manageMembers';
$tester->app->moduleName = 'space';
$tester->app->methodName = 'manageMembers';

su('admin');

$spaceZenTest = new spaceZenTest();

r($spaceZenTest->buildManageMembersDataTest(array(), array())) && p() && e('0');   // 测试传入空formData和空members并验证返回结果为空

$formData = array();
$form = new stdClass();
$form->account = 'newuser1';
$form->role    = 'member';
$form->group   = array(1);
$form->repo    = array();
$formData[] = $form;

r(is_array($spaceZenTest->buildManageMembersDataTest($formData, array()))) && p() && e('1');  // 测试传入有效formData添加新成员并验证返回结果类型

$members = array(
    'newuser1' => (object)array('account' => 'newuser1', 'role' => 'member', 'group' => array(), 'repo' => array())
);

r(is_array($spaceZenTest->buildManageMembersDataTest($formData, $members))) && p() && e('1'); // 测试传入有效formData和现有members并验证返回结果类型

r($spaceZenTest->buildManageMembersDataTest(array(), $members)) && p() && e('0');              // 测试传入空formData和有效members并验证返回结果为空

r(isset($spaceZenTest->buildManageMembersDataTest($formData, array())['space'])) && p() && e('1'); // 测试传入有效数据并验证包含space键
