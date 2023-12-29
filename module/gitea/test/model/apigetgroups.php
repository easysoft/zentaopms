#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetGroups();
timeout=0
cid=0

- 错误的服务器ID @0
- 管理员账号模拟用户权限查询
 - 第0条的name属性 @org1
 - 第1条的name属性 @org_public
- 管理员账号不模拟用户权限查询
 - 第0条的name属性 @org1
 - 第1条的name属性 @org_public
- 没有权限的用户模拟用户权限查询 @0
- 没有权限的用户不模拟用户权限查询
 - 第0条的name属性 @org1
 - 第1条的name属性 @org_public
- 有权限的账号模拟用户权限查询
 - 第0条的name属性 @org1
 - 第1条的name属性 @org_public
- 有权限的账号不模拟用户权限查询
 - 第0条的name属性 @org1
 - 第1条的name属性 @org_public

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

$giteaID = 1;
r($giteaModel->apiGetGroups($giteaID)) && p() && e('0'); // 错误的服务器ID

$giteaID = 4;
r($giteaModel->apiGetGroups($giteaID))        && p('0:name;1:name') && e('org1,org_public'); // 管理员账号模拟用户权限查询
r($giteaModel->apiGetGroups($giteaID, false)) && p('0:name;1:name') && e('org1,org_public'); // 管理员账号不模拟用户权限查询

su('user1');
r($giteaModel->apiGetGroups($giteaID))        && p()                && e('0');               // 没有权限的用户模拟用户权限查询
r($giteaModel->apiGetGroups($giteaID, false)) && p('0:name;1:name') && e('org1,org_public'); // 没有权限的用户不模拟用户权限查询

su('user2');
r($giteaModel->apiGetGroups($giteaID))        && p('0:name;1:name') && e('org1,org_public'); // 有权限的账号模拟用户权限查询
r($giteaModel->apiGetGroups($giteaID, false)) && p('0:name;1:name') && e('org1,org_public'); // 有权限的账号不模拟用户权限查询