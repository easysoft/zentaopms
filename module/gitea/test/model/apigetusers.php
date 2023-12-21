#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetUsers();
timeout=0
cid=0

- 错误的服务器ID @0
- 正确的服务器ID
 - 第0条的realname属性 @gitea
 - 第1条的realname属性 @unittest
- 正确的服务器ID，只查询绑定过的用户第0条的realname属性 @unittest
- 没有权限的用户 @0
- 没有权限的用户，只查询绑定过的用户 @0
- 有权限的用户
 - 第0条的realname属性 @gitea
 - 第1条的realname属性 @unittest
- 有权限的用户，只查询绑定过的用户第0条的realname属性 @unittest

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

$giteaID = 1;
r($giteaModel->apiGetUsers($giteaID)) && p() && e('0'); // 错误的服务器ID

$giteaID = 4;
r($giteaModel->apiGetUsers($giteaID, false)) && p('0:realname;1:realname') && e('gitea,unittest'); // 正确的服务器ID
r($giteaModel->apiGetUsers($giteaID, true))  && p('0:realname') && e('unittest'); // 正确的服务器ID，只查询绑定过的用户

su('user1');
r($giteaModel->apiGetUsers($giteaID, false)) && p() && e('0'); // 没有权限的用户
r($giteaModel->apiGetUsers($giteaID, true))  && p() && e('0'); // 没有权限的用户，只查询绑定过的用户

su('user2');
r($giteaModel->apiGetUsers($giteaID, false)) && p('0:realname;1:realname') && e('gitea,unittest'); // 有权限的用户
r($giteaModel->apiGetUsers($giteaID, true))  && p('0:realname')            && e('unittest');       // 有权限的用户，只查询绑定过的用户