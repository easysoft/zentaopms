#!/usr/bin/env php
<?php

/**

title=测试 userZen::getUserForJSON();
timeout=0
cid=19675

- 执行$result1->password @0
- 执行$result2->deleted @0
- 执行$result3->token) > 0 @1
- 执行$result4属性company @禅道软件
- 执行$result5属性account @admin
- 执行$result5属性realname @管理员
- 执行$result5属性email @admin@test.com

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$table = zenData('user');
$table->loadYaml('user_getuserforjson', false, 2)->gen(10);

su('admin');

$userTest = new userZenTest();

// 创建测试用户对象
$testUser = new stdClass();
$testUser->id = 1;
$testUser->account = 'admin';
$testUser->realname = '管理员';
$testUser->email = 'admin@test.com';
$testUser->password = 'hashedpassword';
$testUser->deleted = '0';
$testUser->role = 'admin';

$result1 = $userTest->getUserForJSONTest($testUser);
$result2 = $userTest->getUserForJSONTest($testUser);
$result3 = $userTest->getUserForJSONTest($testUser);
$result4 = $userTest->getUserForJSONTest($testUser);
$result5 = $userTest->getUserForJSONTest($testUser);

r(isset($result1->password)) && p() && e('0');
r(isset($result2->deleted)) && p() && e('0');
r(strlen($result3->token) > 0) && p() && e('1');
r($result4) && p('company') && e('禅道软件');
r($result5) && p('account') && e('admin');
r($result5) && p('realname') && e('管理员');
r($result5) && p('email') && e('admin@test.com');