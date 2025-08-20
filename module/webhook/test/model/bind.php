#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';
su('admin');

zendata('webhook')->gen(0);

/**

title=测试 webhookModel->bind();
timeout=0
cid=1

- 查看绑定后的webhook信息
 - 属性id @1
 - 属性type @feishuuser
 - 属性name @测试绑定的webhook
 - 属性products @93
 - 属性executions @690
 - 属性desc @测试描述

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

$webhook->bindTest($post, $bind);

global $tester;
$result1 = $tester->loadModel('webhook')->getByID(1);

r($result1) && p('id,type,name,products,executions,desc') && e('1,feishuuser,测试绑定的webhook,93,690,测试描述'); // 查看绑定后的webhook信息