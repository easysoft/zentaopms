#!/usr/bin/env php
<?php

/**

title=测试 docZen::checkPrivForEdit();
timeout=0

- 开放文档允许编辑 @1
- 私有文档作者允许编辑 @1
- 私有文档管理员允许编辑 @1
- 编辑白名单用户允许编辑 @1
- 编辑组用户允许编辑 @1
- 无关用户不能编辑 @0
- 空 groups 不影响其他编辑规则 @1
- 私有库中的开放文档不能被库外用户编辑 @0
- 软删除文档不能编辑 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->app->user->account = 'user1';
$tester->app->user->admin   = false;
$tester->app->user->groups  = array(1, 2);

$docTest = new docZenTest();

$makeLib = function(int $id, string $acl, string $addedBy, string $users = ''): object
{
    $lib = new stdClass();
    $lib->id      = $id;
    $lib->type    = 'custom';
    $lib->product = 0;
    $lib->project = 0;
    $lib->execution = 0;
    $lib->acl     = $acl;
    $lib->addedBy = $addedBy;
    $lib->users   = $users;
    $lib->groups  = '';
    $lib->deleted = 0;
    return $lib;
};

$openLib          = $makeLib(101, 'open', 'admin');
$privateForeignLib = $makeLib(102, 'private', 'admin');

$openDoc = new stdClass();
$openDoc->lib     = 101;
$openDoc->acl     = 'open';
$openDoc->addedBy = 'admin';
$openDoc->users   = '';
$openDoc->groups  = '';

$privateAuthorDoc = new stdClass();
$privateAuthorDoc->lib     = 101;
$privateAuthorDoc->acl     = 'private';
$privateAuthorDoc->addedBy = 'user1';
$privateAuthorDoc->users   = '';
$privateAuthorDoc->groups  = '';

$privateAdminDoc = new stdClass();
$privateAdminDoc->lib     = 101;
$privateAdminDoc->acl     = 'private';
$privateAdminDoc->addedBy = 'admin';
$privateAdminDoc->users   = '';
$privateAdminDoc->groups  = '';

$editUserDoc = new stdClass();
$editUserDoc->lib     = 101;
$editUserDoc->acl     = 'private';
$editUserDoc->addedBy = 'admin';
$editUserDoc->users   = 'user1,user2';
$editUserDoc->groups  = '';

$editGroupDoc = new stdClass();
$editGroupDoc->lib     = 101;
$editGroupDoc->acl     = 'private';
$editGroupDoc->addedBy = 'admin';
$editGroupDoc->users   = '';
$editGroupDoc->groups  = '2,3';

$unrelatedUserDoc = new stdClass();
$unrelatedUserDoc->lib     = 101;
$unrelatedUserDoc->acl     = 'private';
$unrelatedUserDoc->addedBy = 'admin';
$unrelatedUserDoc->users   = 'user2';
$unrelatedUserDoc->groups  = '3,4';

$emptyGroupsDoc = new stdClass();
$emptyGroupsDoc->lib     = 101;
$emptyGroupsDoc->acl     = 'private';
$emptyGroupsDoc->addedBy = 'admin';
$emptyGroupsDoc->users   = 'user1';
$emptyGroupsDoc->groups  = '';

r($docTest->checkPrivForEditTest($openDoc, $openLib)) && p() && e('1');
r($docTest->checkPrivForEditTest($privateAuthorDoc, $openLib)) && p() && e('1');

$tester->app->user->admin = true;
r($docTest->checkPrivForEditTest($privateAdminDoc, $openLib)) && p() && e('1');

$tester->app->user->admin = false;
r($docTest->checkPrivForEditTest($editUserDoc, $openLib)) && p() && e('1');
r($docTest->checkPrivForEditTest($editGroupDoc, $openLib)) && p() && e('1');
r($docTest->checkPrivForEditTest($unrelatedUserDoc, $openLib)) && p() && e('0');
r($docTest->checkPrivForEditTest($emptyGroupsDoc, $openLib)) && p() && e('1');
r($docTest->checkPrivForEditTest($openDoc, $privateForeignLib)) && p() && e('0');

$deletedDoc = clone $openDoc;
$deletedDoc->deleted = 1;
r($docTest->checkPrivForEditTest($deletedDoc, $openLib)) && p() && e('0');
