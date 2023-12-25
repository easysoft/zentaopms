#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->checkPriv();
timeout=0
cid=1

- 超级管理员检查私有项目权限 @1
- 有权限权限用户检查私有项目权限 @1
- 无权限用户检查私有项目权限 @0
- 项目为open时不检查用户权限 @1
- 项目为自定义时无权限用户 @0
- 有权限权限用户检查自定义项目权限 @1

*/

zdTable('user')->gen(10);
zdTable('project')->gen(20);
zdTable('product')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$checkPriv = new repoTest();

$repo = new stdclass();
$repo->projects = '100';
$repo->product  = '100';
$repo->acl = new stdclass();
$repo->acl->acl    = 'private';
$repo->acl->groups = array('qa');
$repo->acl->users  = array('user1');

r($checkPriv->checkPrivTest($repo)) && p() && e('1'); //超级管理员检查私有项目权限
su('user1');
r($checkPriv->checkPrivTest($repo)) && p() && e('1'); //有权限权限用户检查私有项目权限
su('user3');
r($checkPriv->checkPrivTest($repo)) && p() && e('0'); //无权限用户检查私有项目权限
$repo->acl->acl = 'open';
r($checkPriv->checkPrivTest($repo)) && p() && e('1'); //项目为open时不检查用户权限
$repo->acl->acl = 'custom';
r($checkPriv->checkPrivTest($repo)) && p() && e('0'); //项目为自定义时无权限用户
su('user1');
r($checkPriv->checkPrivTest($repo)) && p() && e('1'); //有权限权限用户检查自定义项目权限