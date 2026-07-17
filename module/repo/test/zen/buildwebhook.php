#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildWebhook();
timeout=0
cid=0

- 完整webhook数据 >> 返回webhook对象
- 缺少name字段 >> 返回对象但可能存在errors
- 无效URL >> 返回对象但DAO有errors
- 过长desc >> 返回对象但DAO有errors
- 最小化数据 >> 返回基本对象

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

$validData = array('name' => 'Test Webhook', 'targetURL' => 'https://example.com/hook', 'desc' => 'Test desc', 'triggerEvent' => true, 'customEvent' => array('push'));
r($zenTest->buildWebhookTest($validData, 1)) && p() && e(array());   // 完整webhook数据

$noName = array('targetURL' => 'https://example.com/hook', 'desc' => 'No name');
r($zenTest->buildWebhookTest($noName, 1)) && p() && e(array());      // 缺少name字段

$invalidUrl = array('name' => 'Bad Hook', 'targetURL' => 'not-a-url', 'desc' => 'Bad URL');
r($zenTest->buildWebhookTest($invalidUrl, 1)) && p() && e(array());  // 无效URL

$longDesc = array('name' => 'Long Desc', 'targetURL' => 'https://example.com/hook', 'desc' => str_repeat('x', 120));
r($zenTest->buildWebhookTest($longDesc, 1)) && p() && e(array());    // 过长desc

$minimal = array('name' => 'Minimal', 'targetURL' => 'https://example.com/min');
r($zenTest->buildWebhookTest($minimal, 1)) && p() && e(array());     // 最小化数据
