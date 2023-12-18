#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteUser();
timeout=0
cid=1

- 使用空的userID删除gitlab群组 @0
- 使用错误gitlabID删除群组 @0
- 通过gitlabID,projectID,分支对象正确删除GitLab用户 @1

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

r($gitlab->apiDeleteUser($gitlabID, 0)) && p() && e('0'); //使用空的userID删除gitlab群组
r($gitlab->apiDeleteUser(0, $userID))   && p() && e('0'); //使用错误gitlabID删除群组

$result = $gitlab->apiDeleteUser($gitlabID, $userID);
if(is_null($result)) $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确删除GitLab用户