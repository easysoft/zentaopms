#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

/**

title=测试 gitlabModel::createUser();
timeout=0
cid=1

- 使用空的name创建gitlab用户第name条的0属性 @名称不能为空
- 使用空的username创建gitlab用户第username条的0属性 @用户名不能为空
- 检查二次密码不一致的情况 @二次密码不一致！
- 通过gitlabID,projectID,分支对象正确创建GitLab用户 @1

*/

zenData('pipeline')->gen(5);
zenData('oauth')->gen(5);

$gitlab = new gitlabTest();

$gitlabID  = 1;

$user = new stdclass();
$user->account         = 'admin';
$user->name            = '';
$user->username        = 'apiuser17';
$user->email           = 'apiuser17@test.com';
$user->password        = '123Qwe!@#';
$user->password_repeat = '';

r($gitlab->createUserTest($gitlabID, $user)) && p('name:0') && e('名称不能为空'); //使用空的name创建gitlab用户

$user->name     = 'apiCreatedUser';
$user->username = '';
r($gitlab->createUserTest($gitlabID, $user)) && p('username:0') && e('用户名不能为空'); //使用空的username创建gitlab用户

$user->username = 'apiuser17';
r($gitlab->createUserTest($gitlabID, $user)) && p('password_repeat:0') && e('二次密码不一致！'); //检查二次密码不一致的情况

$user->password_repeat = '123Qwe!@#';
$result = $gitlab->createUserTest($gitlabID, $user); //
if(!empty($result[0]) and $result[0] == 'Email has already been taken') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab用户
