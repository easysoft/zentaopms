#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('webhook')->gen(1);

/**

title=测试 webhookModel->update();
timeout=0
cid=1

- 修改第一个webhook的name属性name @新加的webhook12
- 只修改了name属性name @修改后的数据
- 修改name为空的情况第name条的0属性 @『名称』不能为空。
- 修改secret为空属性secret @~~
- 修改domain为空属性domain @~~
- 修改products为空属性products @~~
- 修改execution为空属性executions @~~
- 修改desc为空属性desc @~~

*/

$webhookTest = new webhookTest();

$webhook = new stdclass();
$webhook->type       = 'dinggroup';
$webhook->name       = '新加的webhook12';
$webhook->url        = 'https://www.baidu.com.';
$webhook->secret     = '122221';
$webhook->domain     = 'http://www.zentaopms.com';
$webhook->products   = '98,100';
$webhook->executions = '696,700';
$webhook->desc       = '测试描述';

$webhook1 = clone $webhook;
$webhook1->name = '修改后的数据';

$webhook2 = clone $webhook;
$webhook2->name = '';

$webhook3 = clone $webhook;
$webhook3->secret = '';

$webhook4 = clone $webhook;
$webhook4->domain = '';

$webhook5 = clone $webhook;
$webhook5->products = '';

$webhook6 = clone $webhook;
$webhook6->executions = '';

$webhook7 = clone $webhook;
$webhook7->desc = '';

$result1 = $webhookTest->updateTest(1, $webhook);
$result2 = $webhookTest->updateTest(1, $webhook1);
$result3 = $webhookTest->updateTest(1, $webhook2);
$result4 = $webhookTest->updateTest(1, $webhook3);
$result5 = $webhookTest->updateTest(1, $webhook4);
$result6 = $webhookTest->updateTest(1, $webhook5);
$result7 = $webhookTest->updateTest(1, $webhook6);
$result8 = $webhookTest->updateTest(1, $webhook7);

r($result1) && p('name')       && e('新加的webhook12');    //修改第一个webhook的name
r($result2) && p('name')       && e('修改后的数据');       //只修改了name
r($result3) && p('name:0')     && e('『名称』不能为空。'); //修改name为空的情况
r($result4) && p('secret')     && e('~~');                 //修改secret为空
r($result5) && p('domain')     && e('~~');                 //修改domain为空
r($result6) && p('products')   && e('~~');                 //修改products为空
r($result7) && p('executions') && e('~~');                 //修改execution为空
r($result8) && p('desc')       && e('~~');                 //修改desc为空