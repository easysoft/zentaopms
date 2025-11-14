#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteUser();
timeout=0
cid=16595

- 使用空的userID删除gitlab群组 @0
- 使用错误的gitlabID删除gitlab群组 @404 User Not Found
- 使用错误的userID删除gitlab群组 @404 Not Found
- 使用错误gitlabID删除群组 @0
- 通过gitlabID,projectID,分支对象正确删除GitLab用户 @1

*/

zenData('pipeline')->gen(5);

global $app;
$app->rawMethod = 'browse';
$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;

/* Create user. */
$user = new stdclass();
$user->name     = 'apiCreatedUser';
$user->username = 'apiuser17';
$user->email    = time() . 'apiuser17@test.com';
$user->password = '123Qwe!@#';
$gitlab->apiCreateUser($gitlabID, $user);

/* Get userID. */
$gitlabUsers = $gitlab->apiGetUsers($gitlabID);
$userID      = 0;
foreach($gitlabUsers as $gitlabUser)
{
    if($gitlabUser->account == 'apiuser17')
    {
        $userID = $gitlabUser->id;
        break;
    }
}

r($gitlab->apiDeleteUser($gitlabID, 0)) && p() && e('0'); //使用空的userID删除gitlab群组
r($gitlab->apiDeleteUser($gitlabID, 1000)) && p('message') && e('404 User Not Found'); //使用错误的userID删除gitlab群组
r($gitlab->apiDeleteUser($gitlabID, -1)) && p('error') && e('404 Not Found'); //使用错误的userID删除gitlab群组
r($gitlab->apiDeleteUser(0, $userID))   && p() && e('0'); //使用错误gitlabID删除群组

$result = $gitlab->apiDeleteUser($gitlabID, $userID);
if(is_null($result)) $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确删除GitLab用户
