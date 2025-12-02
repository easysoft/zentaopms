#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::setSetting();
cid=19777

- 测试设置null配置 @1
- 测试设置空配置 @1
- 测试设置完整的ZAI配置 @1
- 测试设置后验证配置已保存 @1
- 测试设置部分配置信息 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

/* 先清空所有设置 */
$tester->loadModel('setting')->setItem('system.zai.global.setting', '');

/* 测试设置null配置 */
r($zai->setSettingTest(null)) && p() && e('1'); // 测试设置null配置

/* 验证设置为空后的读取结果 */
$emptySetting = $zai->getSettingTest();
r(empty($emptySetting) ? 1 : 0) && p() && e('1'); // 测试设置空配置

/* 测试设置完整配置 */
$completeSetting = new stdClass();
$completeSetting->host = 'newhost.com';
$completeSetting->port = 9999;
$completeSetting->appID = 'newappid456';
$completeSetting->token = 'newtoken456';
$completeSetting->adminToken = 'newadmintoken456';
$completeSetting->zaiTokenTTL = 3600;
r($zai->setSettingTest($completeSetting)) && p() && e('1'); // 测试设置完整的ZAI配置

/* 验证完整配置设置成功 */
$savedSetting = $zai->getSettingTest();
r(isset($savedSetting->host) ? 1 : 0) && p() && e('1'); // 测试设置后验证配置已保存

/* 测试设置部分配置信息 */
$partialSetting = new stdClass();
$partialSetting->host = 'partialhost.com';
$partialSetting->port = 7777;
$partialSetting->appID = 'partialappid789';
// 不设置token和adminToken
r($zai->setSettingTest($partialSetting)) && p() && e('1'); // 测试设置部分配置信息
