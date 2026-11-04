#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 kanbanModel->sendCardNoticeByWebhook();
timeout=0
cid=18194

- 空卡片数组不发送webhook通知 @0
- 3张卡片2个webhook发送6条通知 @6
- 异步通知的字段详情
 - 属性objectType @webhook
 - 属性action @0
 - 属性status @wait
 - 属性createdBy @admin

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('notify')->gen(0);
zenData('user')->gen(3);
zenData('webhook')->gen(0);

$webhookTable = zenData('webhook');
$webhookTable->type->range('default,dinggroup');
$webhookTable->url->range('https://example.com/hook1,https://example.com/hook2');
$webhookTable->sendType->range('async,async');
$webhookTable->gen(2);

su('admin');

$cards = array(
    'admin' => array(
        (object)array('id' => 1, 'name' => '卡片1', 'kanban' => 1, 'deadline' => '2030-01-01'),
        (object)array('id' => 2, 'name' => '卡片2', 'kanban' => 2, 'deadline' => '2020-01-01')
    ),
    'user1' => array(
        (object)array('id' => 3, 'name' => '卡片3', 'kanban' => 3, 'deadline' => '2030-01-01')
    )
);

$kanban = new kanbanModelTest();

r(count($kanban->sendCardNoticeByWebhookTest(array()))) && p() && e('0'); // 空卡片数组不发送webhook通知

$result = $kanban->sendCardNoticeByWebhookTest($cards);
r(count($result)) && p()                                     && e('6');                    // 3张卡片2个webhook发送6条通知
r($result[1])     && p('objectType,action,status,createdBy') && e('webhook,0,wait,admin'); // 异步通知的字段详情
