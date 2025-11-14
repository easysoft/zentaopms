#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 executionModel::accessDenied();
timeout=0
cid=16257

- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail

*/

global $tester;
$tester->app->moduleName = 'execution';
$tester->app->methodName = 'task';
$tester->app->setControlFile();
$tester->loadModel('execution');

try
{
    $tester->execution->accessDenied();
}
catch (Throwable $e)
{
    $result = 'fail';
}

r($result) && p() && e("fail"); // 权限不足跳转
r($result) && p() && e("fail"); // 权限不足跳转
r($result) && p() && e("fail"); // 权限不足跳转
r($result) && p() && e("fail"); // 权限不足跳转
r($result) && p() && e("fail"); // 权限不足跳转
