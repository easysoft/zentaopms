#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(10);

/**

title=测试 commonModel::checkPriv();
timeout=0
cid=1

- 用户没有权限时，返回跳转的URL @{"load":"user-deny-user-create.html"}没有权限
- 用户有权限时，返回TRUE @1

*/

global $tester, $app, $config;

su('user1');
$app->moduleName     = 'user';
$app->methodName     = 'create';
$config->webRoot     = '';
$config->requestType = 'PATH_INFO';

$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('{"load":"user-deny-user-create.html"}没有权限'); // 用户没有权限时，返回跳转的URL

$app->moduleName = 'user';
$app->methodName = 'login';

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('1'); // 用户有权限时，返回TRUE

unset($_SERVER['HTTP_X_REQUESTED_WITH']);