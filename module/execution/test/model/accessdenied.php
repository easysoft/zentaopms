#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 executionModel::accessDenied();
timeout=0
cid=1

*/

global $tester;
$tester->app->moduleName = 'execution';
$tester->app->methodName = 'task';
$tester->app->setControlFile();
$tester->app->setParams();
$tester->loadModel('execution');

try
{
    $tester->execution->accessDenied();
}
catch (Throwable $e)
{
    $result = 'fail';
}

r($result) && p() && e("<html><meta charset='utf-8'/><style>body{background:white}</style><script>window.alert('您无权访问该迭代！')"); // 权限不足跳转
