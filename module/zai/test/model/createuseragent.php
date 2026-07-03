#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::createUserAgent();
timeout=0
cid=0

- 步骤1：无 ZAI 配置时创建失败返回空 @0
- 步骤2：API 返回空时创建失败返回空 @0
- 步骤3：API 返回无 id 时创建失败返回空 @0
- 步骤4：API 成功时返回 agent ID @agent-admin-new
- 步骤5：创建成功后数据库写入 agent 记录 @agent-admin-new

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

/* 设置完整的 ZAI 配置 */
$setting = new stdClass();
$setting->host        = 'testhost.com';
$setting->port        = 8080;
$setting->appID       = 'testappid123';
$setting->token       = 'testtoken123';
$setting->adminToken  = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 步骤2：API 返回空时创建失败返回空 */
r($zai->createUserAgentTest('admin', false)) && p() && e('0'); // 步骤2：API 返回空时创建失败返回空

/* 步骤3：API 返回无 id 时创建失败返回空 */
r($zai->createUserAgentTest('admin', json_encode(array('name' => 'test-agent')))) && p() && e('0'); // 步骤3：API 返回无 id 时创建失败返回空

/* 步骤4：API 成功时返回 agent ID */
r($zai->createUserAgentTest('admin', null, 'agent-admin-new')) && p() && e('agent-admin-new'); // 步骤4：API 成功时返回 agent ID

/* 步骤5：创建成功后数据库写入 agent 记录 */
r($zai->getUserAgentRecordTest('admin')) && p() && e('agent-admin-new'); // 步骤5：创建成功后数据库写入 agent 记录