#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 programModel::accessDenied();
timeout=0
cid=17669

- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail

*/

global $tester;
$tester->app->moduleName = 'program';
$tester->app->methodName = 'product';
$tester->app->setControlFile();
$tester->app->setParams();
$tester->loadModel('program');

try
{
    $tester->program->accessDenied();
}
catch (Throwable $e)
{
    $result = 'fail';
}

r($result) && p() && e('fail'); // 权限不足跳转
r($result) && p() && e('fail'); // 权限不足跳转
r($result) && p() && e('fail'); // 权限不足跳转
r($result) && p() && e('fail'); // 权限不足跳转
r($result) && p() && e('fail'); // 权限不足跳转
