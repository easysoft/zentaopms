#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::recordWebhookLogs();
timeout=0
cid=16678

- 测试记录包含issue信息的webhook日志,验证文件包含JSON字符串 @1
- 测试记录包含bug对象类型的webhook日志,验证文件包含对象类型 @1
- 测试记录包含story对象类型的webhook日志,验证文件包含对象类型 @1
- 测试记录包含task对象类型的webhook日志,验证文件包含对象类型 @1
- 测试记录空JSON内容的webhook日志,验证文件正常创建 @1
- 测试记录带有特殊字符的webhook日志,验证文件正常处理 @1
- 测试验证日志文件包含PHP安全头,防止直接访问 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

$gitlabTest = new gitlabZenTest();

/* 准备测试数据:创建包含issue信息的result对象 */
$result1 = new stdclass;
$result1->issue = new stdclass;
$result1->issue->objectType = 'bug';
$result1->object = new stdclass;
$result1->object->id = 123;
$result1->object->title = 'Test Bug';

$result2 = new stdclass;
$result2->issue = new stdclass;
$result2->issue->objectType = 'bug';
$result2->object = new stdclass;
$result2->object->id = 456;
$result2->object->title = 'Bug Report';

$result3 = new stdclass;
$result3->issue = new stdclass;
$result3->issue->objectType = 'story';
$result3->object = new stdclass;
$result3->object->id = 789;
$result3->object->title = 'User Story';

$result4 = new stdclass;
$result4->issue = new stdclass;
$result4->issue->objectType = 'task';
$result4->object = new stdclass;
$result4->object->id = 101;
$result4->object->title = 'Development Task';

$result5 = new stdclass;
$result5->issue = new stdclass;
$result5->issue->objectType = 'bug';
$result5->object = new stdclass;

$result6 = new stdclass;
$result6->issue = new stdclass;
$result6->issue->objectType = 'bug';
$result6->object = new stdclass;
$result6->object->title = 'Special <>&"\'';

$result7 = new stdclass;
$result7->issue = new stdclass;
$result7->issue->objectType = 'story';
$result7->object = new stdclass;
$result7->object->id = 999;

r(strpos($gitlabTest->recordWebhookLogsTest('{"event":"issue","action":"open"}', $result1), '{"event":"issue","action":"open"}') !== false) && p() && e('1'); // 测试记录包含issue信息的webhook日志,验证文件包含JSON字符串
r(strpos($gitlabTest->recordWebhookLogsTest('{"id":456}', $result2), 'bug') !== false) && p() && e('1'); // 测试记录包含bug对象类型的webhook日志,验证文件包含对象类型
r(strpos($gitlabTest->recordWebhookLogsTest('{"id":789}', $result3), 'story') !== false) && p() && e('1'); // 测试记录包含story对象类型的webhook日志,验证文件包含对象类型
r(strpos($gitlabTest->recordWebhookLogsTest('{"id":101}', $result4), 'task') !== false) && p() && e('1'); // 测试记录包含task对象类型的webhook日志,验证文件包含对象类型
r(strpos($gitlabTest->recordWebhookLogsTest('{}', $result5), '{}') !== false) && p() && e('1'); // 测试记录空JSON内容的webhook日志,验证文件正常创建
r(strpos($gitlabTest->recordWebhookLogsTest('{"title":"test"}', $result6), 'Special') !== false) && p() && e('1'); // 测试记录带有特殊字符的webhook日志,验证文件正常处理
r(strpos($gitlabTest->recordWebhookLogsTest('{"test":"data"}', $result7), '<?php die(); ?>') !== false) && p() && e('1'); // 测试验证日志文件包含PHP安全头,防止直接访问