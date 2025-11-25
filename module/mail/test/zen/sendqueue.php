#!/usr/bin/env php
<?php

/**

title=测试 mailZen::sendQueue();
timeout=0
cid=17042

- 执行mailZenTest模块的sendQueueZenTest方法，参数是$queue1, false 属性result @success
- 执行mailZenTest模块的sendQueueZenTest方法，参数是$queue2, false  @0
- 执行mailZenTest模块的sendQueueZenTest方法，参数是$queue3, false  @0
- 执行mailZenTest模块的sendQueueZenTest方法，参数是$queue4, true  @0
- 执行mailZenTest模块的sendQueueZenTest方法，参数是$queue5, false 属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mailzen.unittest.class.php';

zendata('notify')->loadYaml('notify_sendqueue', false, 2)->gen(5);

su('admin');

$mailZenTest = new mailZenTest();

$queue1 = new stdclass();
$queue1->id = 1;
$queue1->toList = 'admin@test.com';
$queue1->subject = '测试邮件主题';
$queue1->data = '测试邮件内容';
$queue1->ccList = '';
$queue1->merge = false;

$queue2 = new stdclass();
$queue2->id = 999;
$queue2->toList = 'test@test.com';
$queue2->subject = '测试邮件主题';
$queue2->data = '测试邮件内容';
$queue2->ccList = '';
$queue2->merge = false;

$queue3 = new stdclass();
$queue3->id = 2;
$queue3->toList = 'admin@test.com';
$queue3->subject = '测试邮件主题';
$queue3->data = '测试邮件内容';
$queue3->ccList = '';
$queue3->merge = false;

$queue4 = new stdclass();
$queue4->id = 3;
$queue4->toList = 'admin@test.com';
$queue4->subject = '测试邮件主题';
$queue4->data = '测试邮件内容';
$queue4->ccList = '';
$queue4->merge = false;

$queue5 = new stdclass();
$queue5->id = 4;
$queue5->toList = 'invalid-email';
$queue5->subject = '测试邮件主题';
$queue5->data = '测试邮件内容';
$queue5->ccList = '';
$queue5->merge = false;

r($mailZenTest->sendQueueZenTest($queue1, false)) && p('result') && e('success');
r($mailZenTest->sendQueueZenTest($queue2, false)) && p() && e(0);
r($mailZenTest->sendQueueZenTest($queue3, false)) && p() && e(0);
r($mailZenTest->sendQueueZenTest($queue4, true)) && p() && e(0);
r($mailZenTest->sendQueueZenTest($queue5, false)) && p('result') && e('success');