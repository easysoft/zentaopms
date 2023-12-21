#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('log')->gen(0);

/**

title=测试 webhookModel->saveLog();
timeout=0
cid=1

- 查看插入的数据属性url @www.test.com
- 查看插入的数据属性url @www.test2.com
- 查看插入的数据属性url @www.test.com
- 查看插入的数据属性url @www.test2.com

*/

$webhookTest = new webhookTest();

$webhook1 = new stdclass();
$webhook1->id          = 1;
$webhook1->url         = 'www.test.com';
$webhook1->contentType = 'text';

$webhook2 = new stdclass();
$webhook2->id          = 2;
$webhook2->url         = 'www.test2.com';
$webhook2->contentType = 'text';

r($webhookTest->saveLogTest($webhook1, 1, 'data',  'result')) && p('url') && e('www.test.com');   // 查看插入的数据
r($webhookTest->saveLogTest($webhook2, 1, 'data',  'result')) && p('url') && e('www.test2.com');  // 查看插入的数据
r($webhookTest->saveLogTest($webhook1, 2, 'data1', 'result1')) && p('url') && e('www.test.com');  // 查看插入的数据
r($webhookTest->saveLogTest($webhook2, 2, 'data2', 'result2')) && p('url') && e('www.test2.com'); // 查看插入的数据