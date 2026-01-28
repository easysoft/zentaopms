#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 gitlabModel::editUser();
timeout=0
cid=16646

- 使用空的account更新gitlab用户第account条的0属性 @禅道用户不能为空
- 设置了密码的情况下更新gitlab用户 @二次密码不一致！
- 通过gitlabID,用户对象正确更新用户名字 @1
- 通过gitlabID,用户对象错误更新用户名字 @该用户已经被绑定！
- 通过gitlabID,用户对象错误更新用户名字 @该用户已经被绑定！

*/

zenData('pipeline')->gen(5);
zenData('oauth')->gen(5);

global $app;
$app->rawModule = 'gitlab';
$app->rawMethod = 'browse';

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;

/* Create user. */
$user = new stdclass();
$user->name     = 'apiCreatedUser2';
$user->username = 'apiuser19';
$user->email    = 'apiuser19@test.com';
$user->password = '123Qwe!@#';
$gitlab->apiCreateUser($gitlabID, $user);

/* Get userID. */
$gitlabUsers = $gitlab->apiGetUsers($gitlabID);
$userID      = 0;
foreach($gitlabUsers as $gitlabUser)
{
    if($gitlabUser->account == 'apiuser19')
    {
        $userID = $gitlabUser->id;
        break;
    }
}

$user->name            = 'apiUpdatedUser';
$user->password_repeat = '';

$gitlabTest = new gitlabModelTest();
r($gitlabTest->editUserTest($gitlabID, $user)) && p('account:0') && e('禅道用户不能为空'); //使用空的account更新gitlab用户

$user->account = 'admin';
r($gitlabTest->editUserTest($gitlabID, $user)) && p('password_repeat:0') && e('二次密码不一致！'); //设置了密码的情况下更新gitlab用户

unset($user->password);
$user->id = $userID;
r($gitlabTest->editUserTest($gitlabID, $user)) && p() && e('1'); //通过gitlabID,用户对象正确更新用户名字

$user->id = 100000;
r($gitlabTest->editUserTest($gitlabID, $user)) && p('account:0') && e('该用户已经被绑定！'); //通过gitlabID,用户对象错误更新用户名字

$user->id = -1;
r($gitlabTest->editUserTest($gitlabID, $user)) && p('account:0') && e('该用户已经被绑定！'); //通过gitlabID,用户对象错误更新用户名字
