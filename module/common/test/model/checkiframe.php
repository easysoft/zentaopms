#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::checkIframe();
timeout=0
cid=1

- 查看任务一和任务二的描述差异长度 @77
- 查看返回的差异中<ins>标签的位置 @44
- 查看返回的差异中<del>标签的位置 @5

*/

global $tester;
$tester->loadModel('common');

$app->moduleName = 'task';
$app->methodName = 'create';
r($tester->common->checkIframe()) && p() && e('1'); // 查看该页面是否要在iframe中打开

$app->moduleName = 'execution';
$app->methodName = 'task';
r($tester->common->checkIframe()) && p() && e('1'); // 查看该页面是否要在iframe中打开
