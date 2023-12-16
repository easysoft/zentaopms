#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 transfer->mergeConfig();
timeout=0
cid=1

- 测试获取合并配置后的时间字段属性9 @estStarted
- 测试传入模块为空时的时间字段 @assignedDate

*/
global $tester;
$transfer = $tester->loadModel('transfer');

/* 获取Task模块配置信息。*/
/* Get Task module config information. */
$transfer->mergeConfig('task');
$taskDateFields = $transfer->moduleConfig->dateFields;

/* 不传入模块时获取合并信息。*/
/* Get merge information without module. */
$transfer->mergeConfig('');
$dateFields = $transfer->moduleConfig->dateFields;

r($taskDateFields) && p('9') && e('estStarted');   // 测试获取合并配置后的时间字段
r($dateFields)     && p('0') && e('assignedDate'); // 测试传入模块为空时的时间字段
