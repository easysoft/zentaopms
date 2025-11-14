#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::checkMaintenance();
timeout=0
cid=15657

- 检查是否处于维护状态 @0
- 检查是否处于维护状态 @0
- 检查是否处于维护状态 @0
- 检查是否处于维护状态 @0
- 检查是否处于维护状态 @0

*/

global $tester;
$tester->loadModel('common');

$app->moduleName = 'user';
$app->methodName = 'login';
r($tester->common->checkMaintenance()) && p() && e('1'); // 检查是否处于维护状态

$app->moduleName = 'execution';
$app->methodName = 'task';
r($tester->common->checkMaintenance()) && p() && e('1'); // 检查是否处于维护状态

$app->moduleName = 'program';
$app->methodName = 'view';
r($tester->common->checkMaintenance()) && p() && e('1'); // 检查是否处于维护状态

$app->moduleName = 'bug';
$app->methodName = 'view';
r($tester->common->checkMaintenance()) && p() && e('1'); // 检查是否处于维护状态

$app->moduleName = 'task';
$app->methodName = 'view';
r($tester->common->checkMaintenance()) && p() && e('1'); // 检查是否处于维护状态
