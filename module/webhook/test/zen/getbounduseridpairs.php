#!/usr/bin/env php
<?php

/**

title=测试 webhookZen::getBoundUseridPairs();
timeout=0
cid=19713

- 执行webhookTest模块的getBoundUseridPairsTest方法，参数是$dingWebhook, $users, $boundUsers, $oauthUsers 属性bound_admin_id @bound_admin_id
- 执行webhookTest模块的getBoundUseridPairsTest方法，参数是$feishuWebhook, $users, $boundUsers, $oauthUsers 属性oauth_user1_id @用户一
- 执行webhookTest模块的getBoundUseridPairsTest方法，参数是$wechatWebhook, $users, $boundUsers, $oauthUsers 属性bound_priority_id @管理员姓名
- 执行webhookTest模块的getBoundUseridPairsTest方法，参数是$dingWebhook, $users, $boundUsers, $oauthUsers  @0
- 执行webhookTest模块的getBoundUseridPairsTest方法，参数是$dingWebhook, $users, $boundUsers, $oauthUsers  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhookzen.unittest.class.php';

// 准备测试数据
$table = zenData('user');
$table->account->range('admin,user1,user2,user3');
$table->realname->range('管理员,用户一,用户二,用户三');
$table->gen(4);

$webhookTable = zenData('webhook');
$webhookTable->type->range('dinguser,feishuuser,wechatuser');
$webhookTable->name->range('钉钉用户同步,飞书用户同步,微信用户同步');
$webhookTable->secret->range('{"appKey":"key1","appSecret":"secret1","agentId":"agent1"},{"appId":"appid2","appSecret":"secret2"},{"appKey":"key3","appSecret":"secret3","agentId":"agent3"}');
$webhookTable->gen(3);

su('admin');

$webhookTest = new webhookTest();

// 构造测试用户对象
$user1 = new stdClass();
$user1->account = 'admin';
$user1->realname = '管理员';

$user2 = new stdClass();
$user2->account = 'user1';
$user2->realname = '用户一';

$user3 = new stdClass();
$user3->account = 'user2';
$user3->realname = '用户二';

$invalidUser = new stdClass();
$invalidUser->account = 'invalid';
$invalidUser->realname = '无效用户';

// 构造webhook对象
$dingWebhook = new stdClass();
$dingWebhook->type = 'dinguser';
$dingWebhook->secret = new stdClass();
$dingWebhook->secret->appKey = 'test_key';
$dingWebhook->secret->appSecret = 'test_secret';
$dingWebhook->secret->agentId = 'test_agent';

$feishuWebhook = new stdClass();
$feishuWebhook->type = 'feishuuser';
$feishuWebhook->secret = new stdClass();
$feishuWebhook->secret->appId = 'test_appid';
$feishuWebhook->secret->appSecret = 'test_secret';

$wechatWebhook = new stdClass();
$wechatWebhook->type = 'wechatuser';
$wechatWebhook->secret = new stdClass();
$wechatWebhook->secret->appKey = 'test_key';
$wechatWebhook->secret->appSecret = 'test_secret';
$wechatWebhook->secret->agentId = 'test_agent';

// 测试步骤1：正常情况-bound用户映射存在，useridPairs中不存在（返回userid本身）
$users = array($user1);
$boundUsers = array('admin' => 'bound_admin_id');
$oauthUsers = array('bound_admin_id' => '管理员');
r($webhookTest->getBoundUseridPairsTest($dingWebhook, $users, $boundUsers, $oauthUsers)) && p('bound_admin_id') && e('bound_admin_id');

// 测试步骤2：通过realname在oauth中找到userid，useridPairs中存在
$users = array($user2);
$boundUsers = array();
$oauthUsers = array('用户一' => 'oauth_user1_id');
r($webhookTest->getBoundUseridPairsTest($feishuWebhook, $users, $boundUsers, $oauthUsers)) && p('oauth_user1_id') && e('用户一');

// 测试步骤3：bound映射存在，且在反向映射中也存在（应返回反向映射的值）
$users = array($user1);
$boundUsers = array('admin' => 'bound_priority_id');
$oauthUsers = array('管理员姓名' => 'bound_priority_id');
r($webhookTest->getBoundUseridPairsTest($wechatWebhook, $users, $boundUsers, $oauthUsers)) && p('bound_priority_id') && e('管理员姓名');

// 测试步骤4：空用户数组输入
$users = array();
$boundUsers = array('admin' => 'test_id');
$oauthUsers = array('test_id' => '管理员');
r($webhookTest->getBoundUseridPairsTest($dingWebhook, $users, $boundUsers, $oauthUsers)) && p() && e('0');

// 测试步骤5：无匹配用户数据的情况
$users = array($invalidUser);
$boundUsers = array('admin' => 'valid_admin_id');
$oauthUsers = array('valid_admin_id' => '管理员');
r($webhookTest->getBoundUseridPairsTest($dingWebhook, $users, $boundUsers, $oauthUsers)) && p() && e('0');