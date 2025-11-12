#!/usr/bin/env php
<?php

/**

title=测试 reportZen::getReminder();
timeout=0
cid=0

- 测试所有配置都开启时,验证返回的用户数量 @9
- 测试bug配置开启时,验证admin用户的bug数据条数 @29
- 测试todo配置开启时,验证admin用户的todo数据条数 @3
- 测试testTask配置开启时,验证user3用户的testTask数据条数 @2
- 测试验证admin用户对象存在 @1
- 测试验证user1用户对象存在 @1
- 测试验证user3用户对象存在 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$bugTable = zenData('bug')->loadYaml('bug');
$bugTable->gen(100);

$taskTable = zenData('task')->loadYaml('task');
$taskTable->gen(30);

$todoTable = zenData('todo')->loadYaml('todo');
$todoTable->gen(50);

$testtaskTable = zenData('testtask');
$testtaskTable->loadYaml('testtask_getusertesttasks', false, 2)->gen(20);

$userTable = zenData('user');
$userTable->loadYaml('user_getusertesttasks', false, 2)->gen(20);

zenData('project')->loadYaml('execution')->gen(130);

su('admin');

$reportTest = new reportZenTest();

$result = $reportTest->getReminderTest();
r(count($result)) && p() && e('9'); // 测试所有配置都开启时,验证返回的用户数量
r(isset($result['admin']->bugs) ? count($result['admin']->bugs) : 0) && p() && e('29'); // 测试bug配置开启时,验证admin用户的bug数据条数
r(isset($result['admin']->todos) ? count($result['admin']->todos) : 0) && p() && e('3'); // 测试todo配置开启时,验证admin用户的todo数据条数
r(isset($result['user3']->testTasks) ? count($result['user3']->testTasks) : 0) && p() && e('2'); // 测试testTask配置开启时,验证user3用户的testTask数据条数
r(isset($result['admin'])) && p() && e('1'); // 测试验证admin用户对象存在
r(isset($result['user1'])) && p() && e('1'); // 测试验证user1用户对象存在
r(isset($result['user3'])) && p() && e('1'); // 测试验证user3用户对象存在