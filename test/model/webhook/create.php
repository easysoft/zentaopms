#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->create();
cid=1
pid=1

测试全部传入的正常创建 >> 8
测试不传type的情况 >> 9
测试不传name的情况 >> 『名称』不能为空。
测试不传url的情况 >> 『Hook地址』不能为空。
测试不传secret的情况 >> 10
测试不传domain的情况 >> 11
测试不传sendType的情况 >> 『发送方式』不符合格式，应当为:『/sync|async/』。
测试不传products的情况 >> 12
测试不传executions的情况 >> 13
测试不传desc的情况 >> 14

*/

$webhook = new webhookTest();

$post  = array();
$post['type']             = 'dinggroup';
$post['name']             = '新加的webhook';
$post['url']              = 'https://open.feishu.cn/open-apis/bot/v2/hook/173bdc6e-6f37-476d-8134-fd25752f00f3';
$post['secret']           = 'cs0iInSMVWAqhPPL8BcVjf';
$post['agentId']          = '';
$post['appKey']           = '';
$post['appSecret']        = '';
$post['wechatCorpId']     = '';
$post['wechatCorpSecret'] = '';
$post['wechatAgentId']    = '';
$post['feishuAppId']      = '';
$post['feishuAppSecret']  = '';
$post['domain']           = 'http://www.zentaopms.com';
$post['sendType']         = 'sync';
$post['products']         = array('0' => 100);
$post['executions']       = array('0' => 700);
$post['desc']             = '当你老了~~~';

$post1 = $post;
$post1['type']            = '';

$post2 = $post;
$post2['name']            = '';

$post3 = $post;
$post3['url']             = '';

$post4 = $post;
$post4['secret']          = '';

$post5 = $post;
$post5['domain']          = '';

$post6 = $post;
$post6['sendType']        = '';

$post7 = $post;
$post7['products']        = '';

$post8 = $post;
$post8['executions']      = '';

$post9 = $post;
$post9['desc']            = '';

$result1  = $webhook->createTest($post);
$result2  = $webhook->createTest($post1);
$result3  = $webhook->createTest($post2);
$result4  = $webhook->createTest($post3);
$result5  = $webhook->createTest($post4);
$result6  = $webhook->createTest($post5);
$result7  = $webhook->createTest($post6);
$result8  = $webhook->createTest($post7);
$result9  = $webhook->createTest($post8);
$result10 = $webhook->createTest($post9);

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
