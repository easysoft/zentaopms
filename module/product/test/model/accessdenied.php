#!/usr/bin/env php
<?php

/**

title=测试 productModel::accessDenied();
timeout=0
cid=17468

- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail
- 权限不足跳转 @fail

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->app->moduleName = 'product';
$tester->app->methodName = 'index';
$tester->app->setControlFile();
$tester->app->setParams();
$tester->loadModel('product');

try
{
    $tester->product->accessDenied('noPriv');
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
