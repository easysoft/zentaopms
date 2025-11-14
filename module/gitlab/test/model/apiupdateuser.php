#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateUser();
timeout=0
cid=16633

- 使用空的userID创建gitlab群组 @0
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,用户对象正确更新用户名字属性name @apiUpdatedUser
- 通过gitlabID,用户对象错误更新用户名字属性name @404 User Not Found
- 通过gitlabID,用户对象错误更新用户名字属性name @404 Not Found

*/

zenData('pipeline')->gen(5);

global $app;
$app->rawModule = 'gitlab';
$app->rawMethod = 'browse';

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;

/* Create user. */
$user = new stdclass();
$user->name     = 'apiCreatedUser1';
$user->username = 'apiuser18';
$user->email    = 'apiuser18@test.com';
$user->password = '123Qwe!@#';
$gitlab->apiCreateUser($gitlabID, $user);

/* Get userID. */
$gitlabUsers = $gitlab->apiGetUsers($gitlabID);
$userID      = 0;
foreach($gitlabUsers as $gitlabUser)
{
    if($gitlabUser->account == 'apiuser18')
    {
        $userID = $gitlabUser->id;
        break;
    }
}

$user = new stdclass();
$user->name = 'apiUpdatedUser';

r($gitlab->apiUpdateUser($gitlabID, $user)) && p() && e('0'); //使用空的userID创建gitlab群组
r($gitlab->apiUpdateUser(0, $user))         && p() && e('0'); //使用错误gitlabID创建群组

$user->id = $userID;
r($gitlab->apiUpdateUser($gitlabID, $user)) && p('name') && e('apiUpdatedUser'); //通过gitlabID,用户对象正确更新用户名字

$user->id = 100000;
r($gitlab->apiUpdateUser($gitlabID, $user)) && p('message') && e('404 User Not Found'); //通过gitlabID,用户对象错误更新用户名字

$user->id = -1;
r($gitlab->apiUpdateUser($gitlabID, $user)) && p('error') && e('404 Not Found'); //通过gitlabID,用户对象错误更新用户名字
