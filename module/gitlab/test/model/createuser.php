#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::createUser();
timeout=0
cid=16641

- 步骤1：空account参数验证第account条的0属性 @禅道用户不能为空
- 步骤2：空name参数验证第name条的0属性 @名称不能为空
- 步骤3：空username参数验证第username条的0属性 @用户名不能为空
- 步骤4：空email参数验证第email条的0属性 @邮箱不能为空
- 步骤5：空password参数验证第password条的0属性 @密码不能为空
- 步骤6：密码不一致验证第password_repeat条的0属性 @二次密码不一致！
- 步骤7：用户名已存在验证 @Email has already been taken
- 步骤8：邮箱已存在验证 @Username has already been taken

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);
zenData('oauth')->gen(5);

su('admin');

$gitlab = new gitlabModelTest();
$gitlabID = 1;

$baseUser = new stdclass();
$baseUser->account         = 'testuser01';
$baseUser->name            = 'Test User';
$baseUser->username        = 'testuser01';
$baseUser->email           = 'testuser01@example.com';
$baseUser->password        = '123Qwe!@#';
$baseUser->password_repeat = '123Qwe!@#';

$user = clone $baseUser;
$user->account = '';
r($gitlab->createUserTest($gitlabID, $user)) && p('account:0') && e('禅道用户不能为空'); // 步骤1：空account参数验证

$user = clone $baseUser;
$user->name = '';
r($gitlab->createUserTest($gitlabID, $user)) && p('name:0') && e('名称不能为空'); // 步骤2：空name参数验证

$user = clone $baseUser;
$user->username = '';
r($gitlab->createUserTest($gitlabID, $user)) && p('username:0') && e('用户名不能为空'); // 步骤3：空username参数验证

$user = clone $baseUser;
$user->email = '';
r($gitlab->createUserTest($gitlabID, $user)) && p('email:0') && e('邮箱不能为空'); // 步骤4：空email参数验证

$user = clone $baseUser;
$user->password = '';
r($gitlab->createUserTest($gitlabID, $user)) && p('password:0') && e('密码不能为空'); // 步骤5：空password参数验证

$user = clone $baseUser;
$user->password_repeat = 'differentPassword';
r($gitlab->createUserTest($gitlabID, $user)) && p('password_repeat:0') && e('二次密码不一致！'); // 步骤6：密码不一致验证

$user = clone $baseUser;
$user->account = 'admin';
$user->username = 'admin';
r($gitlab->createUserTest($gitlabID, $user)) && p('0') && e('Email has already been taken'); // 步骤7：用户名已存在验证

$user = clone $baseUser;
$user->email = 'admin@zentao.com';
r($gitlab->createUserTest($gitlabID, $user)) && p('0') && e('Username has already been taken'); // 步骤8：邮箱已存在验证