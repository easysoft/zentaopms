#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::checkIframe();
timeout=0
cid=1

- 查看任务一和任务二的描述差异长度 @1
- 查看返回的差异中<ins>标签的位置 查看返回的差异中<del>标签的位置 @1
- 查看实例的终端页面instance-terminal会跳出iframe打开 @1
- 实例的日志页面instance-logs也会跳出iframe打开 @1
- 查看实例的事件页面instance-events会在iframe中打开 @1

*/

global $tester;
$tester->loadModel('common');

$app->moduleName = 'task';
$app->methodName = 'create';
r($tester->common->checkIframe()) && p() && e('1'); // 查看该页面是否要在iframe中打开

$app->moduleName = 'execution';
$app->methodName = 'task';
r($tester->common->checkIframe()) && p() && e('1'); // 查看该页面是否要在iframe中打开

$app->moduleName = 'instance';
$app->methodName = 'terminal';
r($tester->common->checkIframe()) && p(0) && e('1');

$app->moduleName = 'instance';
$app->methodName = 'logs';
r($tester->common->checkIframe()) && p('0') && e('1');

$app->moduleName = 'instance';
$app->methodName = 'events';
r($tester->common->checkIframe()) && p(0) && e('1');
