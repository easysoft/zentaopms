#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->checkPriv();
timeout=0
cid=18034

- 超级管理员检查私有项目权限 @1
- 私有仓库成员检查权限 @1
- 无权限用户检查私有项目权限 @0
- 项目为open时不检查用户权限 @1
- 未知 ACL 下无权限用户 @0
- 未知 ACL 下仓库成员 @0

*/

zenData('user')->gen(10);
global $tester;
$tester->dao->delete()->from(TABLE_DEVOPSREPOUSER)->where('repo')->eq(1)->exec();
$tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => 1, 'account' => 'user1'))->exec();
su('admin');

$checkPriv = new repoModelTest();

$repo = new stdclass();
$repo->id  = 1;
$repo->acl = 'private';

r($checkPriv->checkPrivTest($repo)) && p() && e('1'); // 超级管理员检查私有项目权限
su('user1');
r($checkPriv->checkPrivTest($repo)) && p() && e('1'); // 私有仓库成员检查权限
su('user3');
$repo->acl = 'private';
r($checkPriv->checkPrivTest($repo)) && p() && e('0'); // 无权限用户检查私有项目权限
$repo->acl = 'open';
r($checkPriv->checkPrivTest($repo)) && p() && e('1'); // open 仓库不检查成员权限
$repo->acl = 'custom';
r($checkPriv->checkPrivTest($repo)) && p() && e('0'); // 未知 ACL 下无权限用户
su('user1');
r($checkPriv->checkPrivTest($repo)) && p() && e('0'); // 未知 ACL 下仓库成员同样无权限
