#!/usr/bin/env php
<?php

/**

title=测试 webhookZen::getResponse();
timeout=0
cid=0

- 步骤1：钉钉用户类型属性result @fail
- 步骤2：企业微信用户类型属性result @fail
- 步骤3：飞书用户类型 @<html><meta charset='utf-8'/><style>body{background:white}</style><script>window.alert('Errcode:10003, Errmsg:invalid param')

- 步骤4：未知类型 @</script>
- 执行webhookTest模块的getResponseTest方法  @<html><meta charset='utf-8'/><style>body{background:white}</style><script>if(window.parent) window.parent.$.enableForm(

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhookzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('webhook');
$table->id->range('1-5');
$table->type->range('dinguser,wechatuser,feishuuser,unknown,default');
$table->name->range('钉钉测试,企微测试,飞书测试,未知测试,默认测试');
$table->secret->range('test_secret{5}');
$table->deleted->range('0');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$webhookTest = new webhookTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($webhookTest->getResponseTest((object)array('type' => 'dinguser', 'secret' => (object)array('appKey' => 'test_key', 'appSecret' => 'test_secret', 'agentId' => 'test_agent')))) && p('result') && e('fail'); // 步骤1：钉钉用户类型
r($webhookTest->getResponseTest((object)array('type' => 'wechatuser', 'secret' => (object)array('appKey' => 'wechat_key', 'appSecret' => 'wechat_secret', 'agentId' => 'wechat_agent')))) && p('result') && e('fail'); // 步骤2：企业微信用户类型
r($webhookTest->getResponseTest((object)array('type' => 'feishuuser', 'secret' => (object)array('appId' => 'feishu_id', 'appSecret' => 'feishu_secret')))) && p() && e("<html><meta charset='utf-8'/><style>body{background:white}</style><script>window.alert('Errcode:10003, Errmsg:invalid param')"); // 步骤3：飞书用户类型
r($webhookTest->getResponseTest((object)array('type' => 'unknown', 'secret' => (object)array()))) && p() && e("</script>"); // 步骤4：未知类型
r($webhookTest->getResponseTest((object)array())) && p() && e("<html><meta charset='utf-8'/><style>body{background:white}</style><script>if(window.parent) window.parent.$.enableForm();

</script>"); // 步骤5：空webhook对象