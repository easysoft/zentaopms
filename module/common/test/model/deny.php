#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::deny();
timeout=0
cid=1

- deny中鉴权，有权限时返回TRUE @1
- deny中鉴权，无权限时返回跳转的URL @{"load":"-deny.php?m=user&f=deny&module=execution&method=create"}0

*/
su('admin');
global $tester, $config;
$config->webRoot     = '';
$config->requestType = 'PATH_INFO';

$tester->loadModel('common');

try{
    $result1 = $tester->common->deny('user', 'create');
} catch(Exception $e){
    $result1 = $e->getMessage();
}

r($result1) && p() && e('1'); // deny中鉴权，有权限时返回TRUE

try{
    $result2 = $tester->common->deny('execution', 'create', false);
} catch(Exception $e){
    $result2 = $e->getMessage();
}

r($result2) && p() && e('{"load":"user-deny-execution-create.html"}0'); // deny中鉴权，无权限时返回跳转的URL
