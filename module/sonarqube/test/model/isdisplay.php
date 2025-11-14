#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::isDisplay();
timeout=0
cid=18388

- 超级管理员，检查 edit 方法权限。 @1
- 超级管理员，检查 reportView 方法权限。 @1
- 超级管理员，检查 browseProject 方法权限。 @1
- 超级管理员，检查 browseIssue 方法权限。 @1
- 普通用户，有 space-browse 权限， 检查 reportView 方法权限。 @1
- 普通用户，有 space-browse 权限， 检查 browseProject 方法权限。 @1
- 普通用户，有 space-browse 权限， 检查 browseIssue 方法权限。 @1
- 普通用户，有 space-browse 权限， 检查 edit 方法权限。 @1
- 普通用户，有 space-browse 权限，没有 instance-manage 权限， 检查 edit 方法权限。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(10);

$userGroup = zenData('usergroup');
$userGroup->account->range('user1,user2');
$userGroup->group->range('3,4');
$userGroup->gen(2);

zenData('group')->gen(10);
$groupPriv = zenData('grouppriv');
$groupPriv->group->range('3,4{2}');
$groupPriv->module->range('space,instance');
$groupPriv->method->range('browse,manage');
$groupPriv->gen(3);

global $tester;
$tester->loadModel('sonarqube');

$action    = 'edit';
$sonarqube = new stdclass();
$sonarqube->id    = 1;
$sonarqube->exec  = 1;
$sonarqube->jobID = 1;

su('admin');
r(sonarqubeModel::isDisplay($sonarqube, $action)) && p() && e('1'); //超级管理员，检查 edit 方法权限。

r(sonarqubeModel::isDisplay($sonarqube, 'reportView'))    && p() && e('1'); //超级管理员，检查 reportView 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseProject')) && p() && e('1'); //超级管理员，检查 browseProject 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseIssue'))   && p() && e('1'); //超级管理员，检查 browseIssue 方法权限。

su('user2');
r(sonarqubeModel::isDisplay($sonarqube, 'reportView'))    && p() && e('1'); // 普通用户，有 space-browse 权限， 检查 reportView 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseProject')) && p() && e('1'); // 普通用户，有 space-browse 权限， 检查 browseProject 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseIssue'))   && p() && e('1'); // 普通用户，有 space-browse 权限， 检查 browseIssue 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, $action))         && p() && e('1'); // 普通用户，有 space-browse 权限， 检查 edit 方法权限。

su('user1');
r(sonarqubeModel::isDisplay($sonarqube, $action))         && p() && e('0'); // 普通用户，有 space-browse 权限，没有 instance-manage 权限， 检查 edit 方法权限。