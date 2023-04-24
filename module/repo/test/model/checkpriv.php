#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->checkPriv();
cid=1
pid=1

检查用户权限，返回布尔值 >> 0

*/

$checkPriv = new repoTest();
$repo = new stdclass();
$repo->acl = new stdclass();
$repo->acl->groups = '管理员';
$repo->acl->user   = 'admin';

$result = $checkPriv->checkPrivTest($repo);

r($result) && p($result) && e('0'); //检查用户权限，返回布尔值