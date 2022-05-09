#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->update();
cid=1
pid=1

我这里创建了一个新的webhook并修改他的信息 >> 1
这里我只修改了name >> 『Hook地址』不能为空。
这里修改name为空的情况 >> 『名称』不能为空。
这里修改secret为空 >> 1
这里修改domain为空 >> 1
这里修改products为空 >> 1
这里修改execution为空 >> 1
这里修改desc为空 >> 1

*/

$webhook = new webhookTest();

$create = array();
$create['type']             = 'dinggroup';
$create['name']             = '新加的webhook';
$create['url']              = 'https://www.baidu.com';
$create['secret']           = 12222;
$create['agentId']          = '';
$create['appKey']           = '';
$create['appSecret']        = '';
$create['wechatCorpId']     = '';
$create['wechatCorpSecret'] = '';
$create['wechatAgentId']    = '';
$create['feishuAppId']      = '';
$create['feishuAppSecret']  = '';
$create['domain']           = 'http://www.zentaopms.com';
$create['sendType']         = 'sync';
$create['products']         = array('0' => 100);
$create['executions']       = array('0' => 700);
$create['desc']             = '当你老了~~~';

$update = array();
$update['type']               = 'dinggroup';
$update['name']               = '新加的webhook12';
$update['url']                = 'https://www.baidu.com.';
$update['secret']             = '122221';
$update['domain']             = 'http://www.zentaopms.com';
$update['products']           = array('0' => 100, '1' => 98);
$update['executions']         = array('0' => 700, '1' => 696);
$update['desc']               = '当你老了~~~敲不动了';

$update1 = array();
$update1['name'] = '修改后的数据';

$update2 = array();
$update2['name']       = '';

$update3 = $update;
$update3['secret']     = '';

$update4 = $update;
$update4['domain']     = '';

$update5 = $update;
$update5['products']   = array();

$update6 = $update;
$update6['executions'] = array();

$update7 = $update;
$update7['desc']       = '';

$result1 = $webhook->updateTest($create, $update);
$result2 = $webhook->updateTest($create, $update1);
$result3 = $webhook->updateTest($create, $update2);
$result4 = $webhook->updateTest($create, $update3);
$result5 = $webhook->updateTest($create, $update4);
$result6 = $webhook->updateTest($create, $update5);
$result7 = $webhook->updateTest($create, $update6);
$result8 = $webhook->updateTest($create, $update7);

r($result1) && p()         && e('1');                      //我这里创建了一个新的webhook并修改他的信息
r($result2) && p('url:0')  && e('『Hook地址』不能为空。'); //这里我只修改了name
r($result3) && p('name:0') && e('『名称』不能为空。');     //这里修改name为空的情况
r($result4) && p()         && e('1');                      //这里修改secret为空
r($result5) && p()         && e('1');                      //这里修改domain为空
r($result6) && p()         && e('1');                      //这里修改products为空
r($result7) && p()         && e('1');                      //这里修改execution为空
r($result8) && p()         && e('1');                      //这里修改desc为空