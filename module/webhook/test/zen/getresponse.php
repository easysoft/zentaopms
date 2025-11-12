#!/usr/bin/env php
<?php

/**

title=测试 webhookZen::getResponse();
timeout=0
cid=0

- 执行webhookTest模块的getResponseTest方法，参数是$dingWebhook 属性result @fail
- 执行webhookTest模块的getResponseTest方法，参数是$wechatWebhook)['message']['40013'], 'Errcode:40013') !== false  @1
- 执行webhookTest模块的getResponseTest方法，参数是$feishuWebhook  @`<html>`
- 执行webhookTest模块的getResponseTest方法，参数是$emptyWebhook  @`</script>`
- 执行webhookTest模块的getResponseTest方法，参数是$unknownWebhook  @`<html>`

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhookzen.unittest.class.php';

su('admin');

$webhookTest = new webhookTest();

// 测试步骤1:钉钉类型webhook-返回API错误响应
$dingWebhook = new stdClass();
$dingWebhook->type = 'dinguser';
$dingWebhook->secret = new stdClass();
$dingWebhook->secret->appKey = 'test_ding_key';
$dingWebhook->secret->appSecret = 'test_ding_secret';
$dingWebhook->secret->agentId = 'test_ding_agent';
r($webhookTest->getResponseTest($dingWebhook)) && p('result') && e('fail');

// 测试步骤2:企业微信类型webhook-返回API错误响应数组
$wechatWebhook = new stdClass();
$wechatWebhook->type = 'wechatuser';
$wechatWebhook->secret = new stdClass();
$wechatWebhook->secret->appKey = 'test_wechat_key';
$wechatWebhook->secret->appSecret = 'test_wechat_secret';
$wechatWebhook->secret->agentId = 'test_wechat_agent';
r(strpos($webhookTest->getResponseTest($wechatWebhook)['message']['40013'], 'Errcode:40013') !== false) && p() && e('1');

// 测试步骤3:飞书类型webhook-API调用会输出HTML并跳转
$feishuWebhook = new stdClass();
$feishuWebhook->type = 'feishuuser';
$feishuWebhook->secret = new stdClass();
$feishuWebhook->secret->appId = 'test_feishu_appid';
$feishuWebhook->secret->appSecret = 'test_feishu_secret';
r($webhookTest->getResponseTest($feishuWebhook)) && p() && e('`<html>`');

// 测试步骤4:空webhook对象(不完整的secret)-返回nodept错误后输出HTML
$emptyWebhook = new stdClass();
$emptyWebhook->type = 'dinguser';
$emptyWebhook->secret = new stdClass();
r($webhookTest->getResponseTest($emptyWebhook)) && p() && e('`</script>`');

// 测试步骤5:未知类型webhook-不匹配任何类型,返回空数组后输出HTML
$unknownWebhook = new stdClass();
$unknownWebhook->type = 'unknown_type';
$unknownWebhook->secret = new stdClass();
$unknownWebhook->secret->appKey = 'test_key';
$unknownWebhook->secret->appSecret = 'test_secret';
r($webhookTest->getResponseTest($unknownWebhook)) && p() && e('`<html>`');