#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zenData('project')->loadYaml('project')->gen(2);

/**

title=测试 projectModel->accessDenied();
timeout=0
cid=1

- 执行 checkAccess 方法，检查 session 中 project 的值。 @2
- 执行 accessDenied 方法，检查 session 中 project 的值。 @0
- 检查 result1 是否存在 @1
- 检查 result1 是否包含 self.location='/project.html' @1
- 检查 result2 是否存在 @1
- 检查 result2 的 result @fail
- 检查 result2 的 load属性locate @/accessdenied.php?m=project&f=browse

*/

global $tester, $config;
$tester->app->moduleName = 'project';
$tester->app->methodName = 'browse';
$tester->app->setControlFile();
$tester->app->setParams();
$tester->loadModel('project');

$config->webRoot     = '/';
$config->requestType = 'PATH_INFO';

$tester->project->checkAccess(2, array(2 => ''));
r($_SESSION['project']) && p() && e('2');  // 执行 checkAccess 方法，检查 session 中 project 的值。

try
{
    $tester->project->accessDenied();
}
catch (EndResponseException $e)
{
    $result1 = $e->getContent();
}

r($_SESSION['project']) && p() && e('0'); // 执行 accessDenied 方法，检查 session 中 project 的值。
r((int)isset($result1)) && p() && e('1'); // 检查 result1 是否存在
r((int)strpos($result1, "self.location='/project.html'") !== false) && p() && e('1'); // 检查 result1 是否包含 self.location='/project.html'

$config->requestType = 'GET';
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
try
{
    $tester->project->accessDenied();
}
catch (EndResponseException $e)
{
    $result2 = $e->getContent();
}
r((int)isset($result2)) && p() && e('1'); // 检查 result2 是否存在

$result2 = json_decode($result2, true);
r($result2['result']) && p()         && e('fail'); // 检查 result2 的 result
r($result2['load'])   && p('locate') && e('/accessdenied.php?m=project&f=browse'); // 检查 result2 的 load
