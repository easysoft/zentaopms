#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(2);

/**

title=测试 projectModel->accessDenied();
timeout=0
cid=1

*/

global $tester;
$tester->app->moduleName = 'project';
$tester->app->methodName = 'browse';
$tester->app->setControlFile();
$tester->app->setParams();
$tester->loadModel('project');

$tester->project->checkAccess(2, array(2 => ''));
r($_SESSION['project']) && p() && e('2');

try
{
    $tester->project->accessDenied();
}
catch (EndResponseException $e)
{
}

r($_SESSION['project']) && p() && e('0');
