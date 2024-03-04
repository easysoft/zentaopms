#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateUser();
timeout=0
cid=1

- 使用空的userID创建gitlab群组 @0
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,用户对象正确更新用户名字属性name @apiUpdatedUser

*/

zdTable('pipeline')->gen(5);

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

$user = new stdclass();
$user->name = 'apiUpdatedUser';

r($gitlab->apiUpdateUser($gitlabID, $user)) && p() && e('0'); //使用空的userID创建gitlab群组
r($gitlab->apiUpdateUser(0, $user))         && p() && e('0'); //使用错误gitlabID创建群组

$user->id = $userID;
r($gitlab->apiUpdateUser($gitlabID, $user)) && p('name') && e('apiUpdatedUser'); //通过gitlabID,用户对象正确更新用户名字