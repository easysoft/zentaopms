#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::isDisplay();
cid=0

- 超级管理员，检查 edit 方法权限。 @1
- 超级管理员，检查 reportView 方法权限。 @1
- 超级管理员，检查 browseProject 方法权限。 @1
- 超级管理员，检查 browseIssue 方法权限。 @1
- 普通用户，没有 space-browse 权限， 检查 reportView 方法权限。 @0
- 普通用户，没有 space-browse 权限， 检查 browseProject 方法权限。 @0
- 普通用户，没有 space-browse 权限， 检查 browseIssue 方法权限。 @0
- 普通用户，没有 space-browse 权限， 检查 edit 方法权限。 @0
- 普通用户，有 space-browse 权限，没有 instance-manage 权限， 检查 edit 方法权限。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->loadModel('sonarqube');
$tester->sonarqube->app->user->admin = true;
$tester->sonarqube->app->rawModule   = 'sonarqube';
$tester->sonarqube->app->rawMethod   = 'edit';

$action    = 'edit';
$sonarqube = new stdclass();
$sonarqube->id    = 1;
$sonarqube->exec  = 1;
$sonarqube->jobID = 1;

r(sonarqubeModel::isDisplay($sonarqube, $action)) && p() && e('1'); //超级管理员，检查 edit 方法权限。

r(sonarqubeModel::isDisplay($sonarqube, 'reportView'))    && p() && e('1'); //超级管理员，检查 reportView 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseProject')) && p() && e('1'); //超级管理员，检查 browseProject 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseIssue'))   && p() && e('1'); //超级管理员，检查 browseIssue 方法权限。

$tester->sonarqube->app->user->admin = false;
$tester->sonarqube->app->user->rights['rights'] = array();
r(sonarqubeModel::isDisplay($sonarqube, 'reportView'))    && p() && e('0'); // 普通用户，没有 space-browse 权限， 检查 reportView 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseProject')) && p() && e('0'); // 普通用户，没有 space-browse 权限， 检查 browseProject 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, 'browseIssue'))   && p() && e('0'); // 普通用户，没有 space-browse 权限， 检查 browseIssue 方法权限。
r(sonarqubeModel::isDisplay($sonarqube, $action))         && p() && e('0'); // 普通用户，没有 space-browse 权限， 检查 edit 方法权限。

$tester->sonarqube->app->user->rights['rights'] = array('space' => 'browse');
r(sonarqubeModel::isDisplay($sonarqube, $action))         && p() && e('0'); // 普通用户，有 space-browse 权限，没有 instance-manage 权限， 检查 edit 方法权限。
