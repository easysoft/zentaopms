#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::editUser();
timeout=0
cid=1

- 使用空的userID创建gitlab群组 @0
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,用户对象正确更新用户名字属性name @apiUpdatedUser

*/

zdTable('pipeline')->gen(5);
zdTable('oauth')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;

/* Create user. */
$user = new stdclass();
$user->name     = 'apiCreatedUser';
$user->username = 'apiuser17';
$user->email    = 'apiuser17@test.com';
$user->password = '12345678';
$gitlab->apiCreateUser($gitlabID, $user);

/* Get userID. */
$gitlabUsers = $gitlab->apiGetUsers($gitlabID);
foreach($gitlabUsers as $gitlabUser)
{
    if($gitlabUser->account == 'apiuser17')
    {
        $userID = $gitlabUser->id;
        break;
    }
}

$user->name            = 'apiUpdatedUser';
$user->password_repeat = '';

$gitlabTest = new gitlabTest();
r($gitlabTest->editUserTest($gitlabID, $user)) && p('account:0') && e('禅道用户不能为空'); //使用空的account更新gitlab用户

$user->account = 'admin';
r($gitlabTest->editUserTest($gitlabID, $user)) && p('0') && e('二次密码不一致！'); //设置了密码的情况下更新gitlab用户

unset($user->password);
$user->id = $userID;
r($gitlabTest->editUserTest($gitlabID, $user)) && p() && e('1'); //通过gitlabID,用户对象正确更新用户名字
