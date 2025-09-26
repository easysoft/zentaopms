#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkEntryToken();
timeout=0
cid=0

- 执行commonTest模块的checkEntryTokenTest方法，参数是$entry  @1
- 执行commonTest模块的checkEntryTokenTest方法，参数是$entry  @0
- 执行commonTest模块的checkEntryTokenTest方法，参数是$entry  @1
- 执行commonTest模块的checkEntryTokenTest方法，参数是$entry  @CALLED_TIME
- 执行commonTest模块的checkEntryTokenTest方法，参数是$entry  @ERROR_TIMESTAMP

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

// 准备测试entry对象
$entry = new stdClass();
$entry->code = 'test_entry';
$entry->key = 'abcdef1234567890abcdef1234567890';
$entry->calledTime = time() - 3600;

global $app;
// 初始化server对象
if(!isset($app->server)) {
    $app->server = new stdClass();
}

// 测试步骤1：正确的token验证（无时间戳）
$queryString = 'm=api&f=getModel';
$_GET['token'] = md5(md5($queryString) . $entry->key);
$app->server->query_String = $queryString . '&token=' . $_GET['token'];

r($commonTest->checkEntryTokenTest($entry)) && p() && e('1');

// 测试步骤2：错误的token验证（无时间戳）
$_GET['token'] = 'wrong_token_12345';
$app->server->query_String = $queryString . '&token=' . $_GET['token'];

r($commonTest->checkEntryTokenTest($entry)) && p() && e('0');

// 测试步骤3：正确的时间戳token验证
$currentTime = time() + 100;
$_GET['token'] = md5($entry->code . $entry->key . $currentTime);
$_GET['time'] = $currentTime;
$app->server->query_String = $queryString . '&time=' . $currentTime . '&token=' . $_GET['token'];

r($commonTest->checkEntryTokenTest($entry)) && p() && e('1');

// 测试步骤4：过期时间戳token验证（时间戳小于等于calledTime）
$oldTime = $entry->calledTime - 100;
$_GET['token'] = md5($entry->code . $entry->key . $oldTime);
$_GET['time'] = $oldTime;
$app->server->query_String = $queryString . '&time=' . $oldTime . '&token=' . $_GET['token'];

r($commonTest->checkEntryTokenTest($entry)) && p() && e('CALLED_TIME');

// 测试步骤5：无效时间戳格式token验证（时间戳以4开头）
$invalidTime = '4000000000';
$_GET['token'] = 'invalid_token';
$_GET['time'] = $invalidTime;
$app->server->query_String = $queryString . '&time=' . $invalidTime . '&token=' . $_GET['token'];

r($commonTest->checkEntryTokenTest($entry)) && p() && e('ERROR_TIMESTAMP');