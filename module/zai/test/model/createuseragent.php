#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::createUserAgent();
timeout=0
cid=0

- 步骤1：无 ZAI 配置时创建失败返回空 @0
- 步骤2：ZAI 地址不可达时创建失败返回空 @0
- 步骤3：ZAI 配置缺少 token 时创建失败返回空 @0
- 步骤4：ZAI 配置缺少 host 时创建失败返回空 @0
- 步骤5：失败后数据库未写入 agent 记录 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
zenData('user')->gen(5);
zenData('ai_useragent')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 步骤1：无 ZAI 配置时创建失败返回空 */
r($zai->createUserAgentTest('admin')) && p() && e('0'); // 步骤1：无 ZAI 配置时创建失败返回空

/* 设置完整的 ZAI 配置，但服务地址不可达 */
$setting = new stdClass();
$setting->host        = '127.0.0.1';
$setting->port        = 1;
$setting->appID       = 'testappid123';
$setting->token       = 'testtoken123';
$setting->adminToken  = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 步骤2：ZAI 地址不可达时创建失败返回空 */
r($zai->createUserAgentTest('admin')) && p() && e('0'); // 步骤2：ZAI 地址不可达时创建失败返回空

/* 设置缺少 token 的 ZAI 配置 */
$invalidSetting = new stdClass();
$invalidSetting->host  = '127.0.0.1';
$invalidSetting->port  = 1;
$invalidSetting->appID = 'testappid123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($invalidSetting));

/* 步骤3：ZAI 配置缺少 token 时创建失败返回空 */
r($zai->createUserAgentTest('admin')) && p() && e('0'); // 步骤3：ZAI 配置缺少 token 时创建失败返回空

/* 设置缺少 host 的 ZAI 配置 */
$invalidHostSetting = new stdClass();
$invalidHostSetting->host        = '';
$invalidHostSetting->port        = 1;
$invalidHostSetting->appID       = 'testappid123';
$invalidHostSetting->token       = 'testtoken123';
$invalidHostSetting->adminToken  = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($invalidHostSetting));

/* 步骤4：ZAI 配置缺少 host 时创建失败返回空 */
r($zai->createUserAgentTest('admin')) && p() && e('0'); // 步骤4：ZAI 配置缺少 host 时创建失败返回空

/* 步骤5：失败后数据库未写入 agent 记录 */
r($zai->getUserAgentRecordTest('admin')) && p() && e('0'); // 步骤5：失败后数据库未写入 agent 记录
