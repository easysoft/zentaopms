#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateUser();
timeout=0
cid=1

- 使用空的name创建gitlab用户 @0
- 使用空的username创建gitlab用户 @0
- 使用错误gitlabID创建用户 @0
- 通过gitlabID,projectID,分支对象正确创建GitLab用户 @1
- 使用重复的信息创建gitlab用户属性message @Email has already been taken

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;

$user = new stdclass();
$user->name     = '';
$user->username = 'apiuser17';
$user->email    = 'apiuser17@test.com';
$user->password = '12345678';

r($gitlab->apiCreateUser($gitlabID, $user)) && p() && e('0'); //使用空的name创建gitlab用户

$user->name     = 'apiCreatedUser';
$user->username = '';
r($gitlab->apiCreateUser($gitlabID, $user)) && p() && e('0'); //使用空的username创建gitlab用户
r($gitlab->apiCreateUser(0, $user))         && p() && e('0'); //使用错误gitlabID创建用户

$user->username = 'apiuser17';
$result = $gitlab->apiCreateUser($gitlabID, $user);
if(!empty($result->name) and $result->name == $user->name) $result = true;
if(!empty($result->message) and $result->message == 'Email has already been taken') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab用户

r( $gitlab->apiCreateUser($gitlabID, $user)) && p('message') && e('Email has already been taken'); //使用重复的信息创建gitlab用户