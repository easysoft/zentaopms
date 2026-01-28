#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getToken();
timeout=0
cid=19775

- 测试获取Token时配置不完整情况 @fail
- 测试普通用户正常获取Token @success
- 测试管理员配置不完整情况 @fail
- 测试管理员正常获取Token @success
- 测试验证Token配置TTL设置 @1200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试配置不完整的情况 */
$incompleteConfig = new stdClass();
$incompleteConfig->host = 'testhost.com';
$incompleteConfig->port = 8080;
// 缺少token和appID
r($zai->getTokenTest($incompleteConfig)) && p('result') && e('fail'); // 测试获取Token时配置不完整情况

/* 设置完整的ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$setting->zaiTokenTTL = 1200;

$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试普通用户获取Token */
r($zai->getTokenTest(null, false)) && p('result') && e('success'); // 测试普通用户正常获取Token

/* 测试管理员配置不完整情况 */
$adminIncompleteConfig = new stdClass();
$adminIncompleteConfig->host = 'testhost.com';
$adminIncompleteConfig->port = 8080;
$adminIncompleteConfig->appID = 'testappid123';
$adminIncompleteConfig->token = 'testtoken123';
// 缺少adminToken
r($zai->getTokenTest($adminIncompleteConfig, true)) && p('result') && e('fail'); // 测试管理员配置不完整情况

/* 测试管理员获取Token */
r($zai->getTokenTest(null, true)) && p('result') && e('success'); // 测试管理员正常获取Token

/* 测试验证Token配置TTL设置 */
$currentSetting = $zai->getSettingTest();
r($currentSetting->zaiTokenTTL) && p() && e('1200'); // 测试验证Token配置TTL设置
