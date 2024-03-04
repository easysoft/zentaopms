#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::apiPost();
timeout=0
cid=1

- 用host url 发送一个请求 @success
- 用host ID 发送一个请求 @success
- 用不合规范的host url 发送一个请求 @return null
- 用不存在host ID 发送一个请求 @return null

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 1;
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

$gitlabTest = new gitlabTest();

$hostID  = 1;
$host    = 'https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w';
$host2   = 'abc.com';
$api     = '/user';
$options = array(CURLOPT_CUSTOMREQUEST => 'PUT');

r($gitlabTest->apiPostTest($host, $api, $user, $options))   && p() && e('success'); //用host url 发送一个请求
r($gitlabTest->apiPostTest($hostID, $api, $user, $options)) && p() && e('success'); //用host ID 发送一个请求
r($gitlabTest->apiPostTest($host2, $api, $user, $options))  && p() && e('return null'); //用不合规范的host url 发送一个请求
r($gitlabTest->apiPostTest(0, $api, $user, $options))       && p() && e('return null'); //用不存在host ID 发送一个请求