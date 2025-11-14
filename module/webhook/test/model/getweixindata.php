#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getWeixinData();
timeout=0
cid=19702

- 测试有mobile参数时的消息类型属性msgtype @text
- 测试有mobile参数时的文本内容第text条的content属性 @测试消息
- 测试无mobile参数时的消息类型属性msgtype @markdown
- 测试无mobile参数时的文本内容第markdown条的content属性 @markdown测试
- 测试空字符串文本内容处理第markdown条的content属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

su('admin');

$webhook = new webhookTest();

r($webhook->getWeixinDataTest('测试消息', '13800138000')) && p('msgtype') && e('text'); // 测试有mobile参数时的消息类型
r($webhook->getWeixinDataTest('测试消息', '13800138000')) && p('text:content') && e('测试消息'); // 测试有mobile参数时的文本内容
r($webhook->getWeixinDataTest('markdown测试', '')) && p('msgtype') && e('markdown'); // 测试无mobile参数时的消息类型
r($webhook->getWeixinDataTest('markdown测试', '')) && p('markdown:content') && e('markdown测试'); // 测试无mobile参数时的文本内容
r($webhook->getWeixinDataTest('', '')) && p('markdown:content') && e('~~'); // 测试空字符串文本内容处理