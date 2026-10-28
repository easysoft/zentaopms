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

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->app->user->account = 'user1';
$tester->app->user->admin   = false;
$tester->app->user->groups  = array(1, 2);

$docTest = new docZenTest();

$openDoc = new stdClass();
$openDoc->acl     = 'open';
$openDoc->addedBy = 'admin';
$openDoc->users   = '';
$openDoc->groups  = '';

$privateAuthorDoc = new stdClass();
$privateAuthorDoc->acl     = 'private';
$privateAuthorDoc->addedBy = 'user1';
$privateAuthorDoc->users   = '';
$privateAuthorDoc->groups  = '';

$privateAdminDoc = new stdClass();
$privateAdminDoc->acl     = 'private';
$privateAdminDoc->addedBy = 'admin';
$privateAdminDoc->users   = '';
$privateAdminDoc->groups  = '';

$editUserDoc = new stdClass();
$editUserDoc->acl     = 'private';
$editUserDoc->addedBy = 'admin';
$editUserDoc->users   = 'user1,user2';
$editUserDoc->groups  = '';

$editGroupDoc = new stdClass();
$editGroupDoc->acl     = 'private';
$editGroupDoc->addedBy = 'admin';
$editGroupDoc->users   = '';
$editGroupDoc->groups  = '2,3';

$unrelatedUserDoc = new stdClass();
$unrelatedUserDoc->acl     = 'private';
$unrelatedUserDoc->addedBy = 'admin';
$unrelatedUserDoc->users   = 'user2';
$unrelatedUserDoc->groups  = '3,4';

$emptyGroupsDoc = new stdClass();
$emptyGroupsDoc->acl     = 'private';
$emptyGroupsDoc->addedBy = 'admin';
$emptyGroupsDoc->users   = 'user1';
$emptyGroupsDoc->groups  = '';

r($docTest->checkPrivForEditTest($openDoc)) && p() && e('1');
r($docTest->checkPrivForEditTest($privateAuthorDoc)) && p() && e('1');

$tester->app->user->admin = true;
r($docTest->checkPrivForEditTest($privateAdminDoc)) && p() && e('1');

$tester->app->user->admin = false;
r($docTest->checkPrivForEditTest($editUserDoc)) && p() && e('1');
r($docTest->checkPrivForEditTest($editGroupDoc)) && p() && e('1');
r($docTest->checkPrivForEditTest($unrelatedUserDoc)) && p() && e('0');
r($docTest->checkPrivForEditTest($emptyGroupsDoc)) && p() && e('1');
