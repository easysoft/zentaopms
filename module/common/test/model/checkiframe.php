#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::checkIframe();
timeout=0
cid=15655

- 查看该页面是否要在iframe中打开 @0
- 查看该页面是否要在iframe中打开 @0
- 查看该页面是否要在iframe中打开 @0
- 查看该页面是否要在iframe中打开 @0
- 查看该页面是否要在iframe中打开 @0

*/

global $tester;
$tester->loadModel('common');

$app->moduleName = 'task';
$app->methodName = 'create';
r($tester->common->checkIframe()) && p() && e('0'); // 查看该页面是否要在iframe中打开

$app->moduleName = 'execution';
$app->methodName = 'task';
r($tester->common->checkIframe()) && p() && e('0'); // 查看该页面是否要在iframe中打开

$app->moduleName = 'instance';
$app->methodName = 'terminal';
r($tester->common->checkIframe()) && p() && e('0'); // 查看该页面是否要在iframe中打开

$app->moduleName = 'instance';
$app->methodName = 'logs';
r($tester->common->checkIframe()) && p() && e('0'); // 查看该页面是否要在iframe中打开

$app->moduleName = 'instance';
$app->methodName = 'events';
r($tester->common->checkIframe()) && p() && e('0'); // 查看该页面是否要在iframe中打开
