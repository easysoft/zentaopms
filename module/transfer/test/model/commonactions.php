#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';

su('admin');

/**

title=测试 transfer->commonActions();
timeout=0
cid=1

- 测试获取Task模块创建的必填字段第execution条的title属性 @execution
- 测试获取Task模块legendEffort语言项属性legendEffort @工时信息
- 测试获取Task模块report下charts:tasksPerModule语言项第charts条的tasksPerModule属性 @按模块任务数统计
- 测试获取Task模块创建的必填字段
 - 第id条的title属性 @ID
 - 第status条的title属性 @状态

*/
global $tester;
$transfer = $tester->loadModel('transfer');

/* 获取Task模块配置信息。*/
/* Get Task module config information. */
$transfer->commonActions('task');
$moduleConfig     = $transfer->moduleConfig->dtable->fieldList;
$moduleLang       = $transfer->moduleLang;
$moduleFieldList  = $transfer->moduleFieldList;

r($moduleConfig)       && p('execution:title')       && e('execution');        // 测试获取Task模块创建的必填字段
r($moduleLang)         && p('legendEffort')          && e('工时信息');         // 测试获取Task模块legendEffort语言项
r($moduleLang->report) && p('charts:tasksPerModule') && e('按模块任务数统计'); // 测试获取Task模块report下charts:tasksPerModule语言项
r($moduleFieldList)    && p('id:title;status:title') && e('ID;状态');          // 测试获取Task模块创建的必填字段
