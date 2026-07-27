#!/usr/bin/env php
<?php

/**

title=测试 spaceZen::buildManageMembersFields();
timeout=0
cid=0

- 测试有效空间ID=1构建管理成员字段并验证结果类型 @1
- 测试有效空间ID=1构建管理成员字段包含account选项 @1
- 测试有效空间ID=1构建管理成员字段包含role选项 @1
- 测试有效空间ID=1构建管理成员字段包含group选项 @1
- 测试无效空间ID=0构建管理成员字段并验证结果类型 @1

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

r(is_array($spaceZenTest->buildManageMembersFieldsTest(1))) && p() && e('1');                // 测试有效空间ID=1构建管理成员字段并验证结果类型
r(isset($spaceZenTest->buildManageMembersFieldsTest(1)['account'])) && p() && e('1');        // 测试有效空间ID=1构建管理成员字段包含account选项
r(isset($spaceZenTest->buildManageMembersFieldsTest(1)['role'])) && p() && e('1');           // 测试有效空间ID=1构建管理成员字段包含role选项
r(isset($spaceZenTest->buildManageMembersFieldsTest(1)['group'])) && p() && e('1');          // 测试有效空间ID=1构建管理成员字段包含group选项
r(is_array($spaceZenTest->buildManageMembersFieldsTest(0))) && p() && e('1');                // 测试无效空间ID=0构建管理成员字段并验证结果类型
