#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::setSetting();
cid=0

- 测试设置null配置 >> 期望清空设置
- 测试设置空配置 >> 期望清空设置
- 测试设置null配置 >> 期望清空设置
- 测试设置完整的ZAI配置 >> 期望正确保存

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
r($zai->getSettingTest()) && p() && e('0'); // 测试设置空配置

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
r($zai->getSettingTest()) && p('host,port,appID,token') && e('newhost.com,9999,newappid456,newtoken456'); // 测试设置后验证配置已保存
