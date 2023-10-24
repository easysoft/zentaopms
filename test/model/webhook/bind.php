#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->bind();
cid=1
pid=1

绑定admin账号给飞书账号的白袁李（创建了测试用户飞书管理端，然后通过create方法创建了一条数据，关联飞书里的一个用户，页面验证成功绑定） >> 1

*/

$webhook = new webhookTest();

$post = array();
$post['type']                        = 'feishuuser';
$post['name']                        = '测试绑定的webhook';
$post['url']                         = '';
$post['secret']                      = '';
$post['agentId']                     = '';
$post['appKey']                      = '';
$post['appSecret']                   = '';
$post['wechatCorpId']                = '';
$post['wechatCorpSecret']            = '';
$post['wechatAgentId']               = '';
$post['feishuAppId']                 = 'cli_a2e1f12e6d785013';
$post['feishuAppSecret']             = 'Pk4rYTwtFJveepj4JDfPCciSgUqBJYM5';
$post['domain']                      = 'http://www.zentaopms.com';
$post['sendType']                    = 'sync';
$post['products']                    = array('0' => 93);
$post['executions']                  = array('0' => 690);
$post['desc']                        = '当你老了~~~';

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

r($result1) && p() && e('1'); //绑定admin账号给飞书账号的白袁李（创建了测试用户飞书管理端，然后通过create方法创建了一条数据，关联飞书里的一个用户，页面验证成功绑定）
