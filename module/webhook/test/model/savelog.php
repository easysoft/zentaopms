#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::saveLog();
timeout=0
cid=19704

- 执行webhookTest模块的saveLogTest方法，参数是$webhook1, 101, 'test data', 'test result'
 - 属性objectType @webhook
 - 属性objectID @1
 - 属性url @https://www.test.com/webhook
- 执行webhookTest模块的saveLogTest方法，参数是$webhook2, 102, 'plain text data', 'response text'
 - 属性contentType @text/plain
 - 属性data @plain text data
- 执行webhookTest模块的saveLogTest方法，参数是$webhook3, 103, '', '' 属性objectID @3
- 执行webhookTest模块的saveLogTest方法，参数是$webhook1, 104, 'different data', 'different result'
 - 属性objectID @1
 - 属性action @104
- 执行$saveResult @1
- 执行webhookTest模块的saveLogTest方法，参数是$webhook2, 999, 'action test data', 'action test result'
 - 属性action @999
 - 属性objectID @2
- 执行webhookTest模块的saveLogTest方法，参数是$webhook3, 106, 'special chars: 中文', 'result with special chars'
 - 属性data @special chars: 中文
 - 属性contentType @application/xml

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

zenData('log')->gen(0);

su('admin');

$webhookTest = new webhookTest();

$webhook1 = new stdclass();
$webhook1->id          = 1;
$webhook1->url         = 'https://www.test.com/webhook';
$webhook1->contentType = 'application/json';

$webhook2 = new stdclass();
$webhook2->id          = 2;
$webhook2->url         = 'https://api.example.com/hook';
$webhook2->contentType = 'text/plain';

$webhook3 = new stdclass();
$webhook3->id          = 3;
$webhook3->url         = 'https://secure.webhook.com/endpoint';
$webhook3->contentType = 'application/xml';

r($webhookTest->saveLogTest($webhook1, 101, 'test data', 'test result')) && p('objectType,objectID,url') && e('webhook,1,https://www.test.com/webhook');

r($webhookTest->saveLogTest($webhook2, 102, 'plain text data', 'response text')) && p('contentType,data') && e('text/plain,plain text data');

r($webhookTest->saveLogTest($webhook3, 103, '', '')) && p('objectID') && e('3');

r($webhookTest->saveLogTest($webhook1, 104, 'different data', 'different result')) && p('objectID,action') && e('1,104');

$saveResult = $webhookTest->objectModel->saveLog($webhook2, 105, 'test data', 'test result');
r($saveResult) && p() && e('1');

r($webhookTest->saveLogTest($webhook2, 999, 'action test data', 'action test result')) && p('action,objectID') && e('999,2');

r($webhookTest->saveLogTest($webhook3, 106, 'special chars: 中文', 'result with special chars')) && p('data,contentType') && e('special chars: 中文,application/xml');