#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::isDisplay();
timeout=0
cid=1

- 用户1检查browseBranch权限 @1
- 用户1检查browseTag权限 @1
- 用户1检查manageBranchPriv权限 @0
- 用户1检查manageTagPriv权限 @0
- 用户1检查manageProjectMembers权限 @0
- 用户1检查createWebhook权限 @0
- 用户1检查importIssue权限 @0
- 用户1检查editProject权限 @0
- 用户1检查deleteProject权限 @0
- 用户2检查browseBranch权限 @1
- 用户2检查browseTag权限 @1
- 用户2检查manageBranchPriv权限 @1
- 用户2检查manageTagPriv权限 @1
- 用户2检查manageProjectMembers权限 @1
- 用户2检查createWebhook权限 @1
- 用户2检查importIssue权限 @1
- 用户2检查editProject权限 @1
- 用户2检查deleteProject权限 @1

*/

zdTable('user')->gen(10);

$userGroup = zdTable('usergroup');
$userGroup->account->range('user1,user2');
$userGroup->group->range('3,4');
$userGroup->gen(2);

zdTable('group')->gen(10);
$groupPriv = zdTable('grouppriv');
$groupPriv->group->range('3,4{2}');
$groupPriv->module->range('space,instance');
$groupPriv->method->range('browse,manage');
$groupPriv->gen(3);

$gitlab = $tester->loadModel('gitlab');

su('user1');
r($gitlab->isDisplay('browseBranch'))         && p() && e('1'); //用户1检查browseBranch权限
r($gitlab->isDisplay('browseTag'))            && p() && e('1'); //用户1检查browseTag权限
r($gitlab->isDisplay('manageBranchPriv'))     && p() && e('0'); //用户1检查manageBranchPriv权限
r($gitlab->isDisplay('manageTagPriv'))        && p() && e('0'); //用户1检查manageTagPriv权限
r($gitlab->isDisplay('manageProjectMembers')) && p() && e('0'); //用户1检查manageProjectMembers权限
r($gitlab->isDisplay('createWebhook'))        && p() && e('0'); //用户1检查createWebhook权限
r($gitlab->isDisplay('importIssue'))          && p() && e('0'); //用户1检查importIssue权限
r($gitlab->isDisplay('editProject'))          && p() && e('0'); //用户1检查editProject权限
r($gitlab->isDisplay('deleteProject'))        && p() && e('0'); //用户1检查deleteProject权限

su('user2');
r($gitlab->isDisplay('browseBranch'))         && p() && e('1'); //用户2检查browseBranch权限
r($gitlab->isDisplay('browseTag'))            && p() && e('1'); //用户2检查browseTag权限
r($gitlab->isDisplay('manageBranchPriv'))     && p() && e('1'); //用户2检查manageBranchPriv权限
r($gitlab->isDisplay('manageTagPriv'))        && p() && e('1'); //用户2检查manageTagPriv权限
r($gitlab->isDisplay('manageProjectMembers')) && p() && e('1'); //用户2检查manageProjectMembers权限
r($gitlab->isDisplay('createWebhook'))        && p() && e('1'); //用户2检查createWebhook权限
r($gitlab->isDisplay('importIssue'))          && p() && e('1'); //用户2检查importIssue权限
r($gitlab->isDisplay('editProject'))          && p() && e('1'); //用户2检查editProject权限
r($gitlab->isDisplay('deleteProject'))        && p() && e('1'); //用户2检查deleteProject权限
