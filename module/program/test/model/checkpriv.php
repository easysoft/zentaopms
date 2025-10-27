#!/usr/bin/env php
<?php

/**

title=测试 programModel::checkPriv();
timeout=0
cid=0

- 管理员用户验证有效项目集权限 @1
- 普通用户验证有权限的项目集 @1
- 普通用户验证无权限的项目集 @0
- 验证空ID或0的项目集 @0
- 验证负数ID的项目集 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$program = zenData('project');
$program->id->range('1-10');
$program->type->range('program{5},project{5}');
$program->name->range('项目集1,项目集2,项目集3,项目集4,项目集5,项目1,项目2,项目3,项目4,项目5');
$program->status->range('wait{3},doing{4},suspended{2},closed{1}');
$program->deleted->range('0{9},1{1}');
$program->gen(10);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->password->range('123456{5}');
$user->role->range('admin{1},qa{2},dev{1},pm{1}');
$user->deleted->range('0{5}');
$user->gen(5);

$userview = zenData('userview');
$userview->account->range('admin,user1,user2,user3,user4');
$userview->programs->range('1,2,3,2,3,1,1,2,3,4');
$userview->gen(5);

su('admin');

global $tester;
$tester->loadModel('program');

r($tester->program->checkPriv(1)) && p() && e('1'); // 管理员用户验证有效项目集权限
su('user1');
r($tester->program->checkPriv(2)) && p() && e('1'); // 普通用户验证有权限的项目集
r($tester->program->checkPriv(5)) && p() && e('0'); // 普通用户验证无权限的项目集
r($tester->program->checkPriv(0)) && p() && e('0'); // 验证空ID或0的项目集
r($tester->program->checkPriv(-1)) && p() && e('0'); // 验证负数ID的项目集