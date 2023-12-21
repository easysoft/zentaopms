#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->bind();
timeout=0
cid=1

- 查看是否绑定成功 @1

*/

$webhook = new webhookTest();

$post = new stdclass();
$post->type              = 'feishuuser';
$post->name              = '测试绑定的webhook';
$post->url               = '';
$post->secret            = '';
$post->agentId           = '';
$post->appKey            = '';
$post->appSecret         = '';
$post->wechatCorpId      = '';
$post->wechatCorpSecret  = '';
$post->wechatAgentId     = '';
$post->feishuAppId       = 'cli_a2e1f12e6d785013';
$post->feishuAppSecret   = 'Pk4rYTwtFJveepj4JDfPCciSgUqBJYM5';
$post->domain            = 'http://www.zentaopms.com';
$post->sendType          = 'sync';
$post->products          = '93';
$post->executions        = '690';
$post->desc              = '测试描述';

$bind = array();
$bind['userid'] = array();
$bind['userid']['admin']             = 'ou_584a4988c0cf7aa997ccd71b57d740ab';
$bind['userid']['program1whitelist'] = '';
$bind['userid']['program1whitelist'] = '';
$bind['userid']['user3']             = '';
$bind['userid']['user4']             = '';
$bind['userid']['user5']             = '';
$bind['userid']['user6']             = '';
$bind['userid']['user7']             = '';
$bind['userid']['user8']             = '';
$bind['userid']['user9']             = '';
$bind['userid']['user10']            = '';
$bind['userid']['user11']            = '';
$bind['userid']['user12']            = '';
$bind['userid']['user13']            = '';
$bind['userid']['user14']            = '';

$result1 = $webhook->bindTest($post, $bind);

r($result1) && p() && e('1'); // 查看是否绑定成功