#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('webhook')->gen(7);

/**

title=测试 webhookModel->create();
timeout=0
cid=1

- 测试全部传入的正常创建 @8
- 测试不传type的情况 @9
- 测试不传name的情况第name条的0属性 @『名称』不能为空。
- 测试不传url的情况第url条的0属性 @『Hook地址』不能为空。
- 测试不传secret的情况 @10
- 测试不传domain的情况 @11
- 测试不传sendType的情况第sendType条的0属性 @『发送方式』不符合格式，应当为:『/sync|async/』。
- 测试不传products的情况 @12
- 测试不传executions的情况 @13

*/

$webhookTest = new webhookTest();

$webhook = new stdclass();
$webhook->type             = 'dinggroup';
$webhook->name             = '新加的webhook';
$webhook->url              = 'https://open.feishu.cn/open-apis/bot/v2/hook/173bdc6e-6f37-476d-8134-fd25752f00f3';
$webhook->secret           = 'cs0iInSMVWAqhPPL8BcVjf';
$webhook->agentId          = '';
$webhook->appKey           = '';
$webhook->appSecret        = '';
$webhook->wechatCorpId     = '';
$webhook->wechatCorpSecret = '';
$webhook->wechatAgentId    = '';
$webhook->feishuAppId      = '';
$webhook->feishuAppSecret  = '';
$webhook->domain           = 'http://www.zentaopms.com';
$webhook->sendType         = 'sync';
$webhook->products         = '100';
$webhook->executions       = '700';
$webhook->desc             = '测试描述';

$webhook1 = clone $webhook;
$webhook1->type = '';

$webhook2 = clone $webhook;
$webhook2->name = '';

$webhook3 = clone $webhook;
$webhook3->url = '';

$webhook4 = clone $webhook;
$webhook4->secret = '';

$webhook5 = clone $webhook;
$webhook5->domain = '';

$webhook6 = clone $webhook;
$webhook6->sendType = '';

$webhook7 = clone $webhook;
$webhook7->products = '';

$webhook8 = clone $webhook;
$webhook8->executions = '';

$webhook9 = clone $webhook;
$webhook9->desc = '';

$result1  = $webhookTest->createTest($webhook);
$result2  = $webhookTest->createTest($webhook1);
$result3  = $webhookTest->createTest($webhook2);
$result4  = $webhookTest->createTest($webhook3);
$result5  = $webhookTest->createTest($webhook4);
$result6  = $webhookTest->createTest($webhook5);
$result7  = $webhookTest->createTest($webhook6);
$result8  = $webhookTest->createTest($webhook7);
$result9  = $webhookTest->createTest($webhook8);
$result10 = $webhookTest->createTest($webhook9);

r($result1)  && p()             && e('8');                                                 //测试全部传入的正常创建
r($result2)  && p()             && e('9');                                                 //测试不传type的情况
r($result3)  && p('name:0')     && e('『名称』不能为空。');                                //测试不传name的情况
r($result4)  && p('url:0')      && e('『Hook地址』不能为空。');                            //测试不传url的情况
r($result5)  && p()             && e('10');                                                //测试不传secret的情况
r($result6)  && p()             && e('11');                                                //测试不传domain的情况
r($result7)  && p('sendType:0') && e('『发送方式』不符合格式，应当为:『/sync|async/』。'); //测试不传sendType的情况
r($result8)  && p()             && e('12');                                                //测试不传products的情况
r($result9)  && p()             && e('13');                                                //测试不传executions的情况
R($result10) && p()             && e('14');                                                //测试不传desc的情况