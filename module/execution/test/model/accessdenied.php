#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 executionModel::accessDenied();
cid=1
pid=1

权限不足跳转 >> html

*/

global $tester;
$tester->app->moduleName = 'execution';
$tester->app->methodName = 'task';
$tester->app->setControlFile();
$tester->app->setParams();
$tester->loadModel('execution');

try
{
    $result = $tester->execution->accessDenied();
}
catch (EndResponseException $e)
{
    $result = $e->getContent();
}

r($result) && p() && e('fail'); // 权限不足跳转
