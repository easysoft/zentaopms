#!/usr/bin/env php
<?php

/**

title=测试 customZen::checkEmptyKeys();
timeout=0
cid=0

- 步骤1：有效的key和value组合 @1
- 步骤2：key存在但value为空字符串 @value_empty_error
- 步骤3：key为空的情况 @1
- 步骤4：多个空key处理逻辑 @1
- 步骤5：混合有效和无效数据测试 @value_empty_error

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

// 2. zendata数据准备
$table = zenData('lang');
$table->lang->range('zh-cn,en,de,fr');
$table->module->range('story,task,bug,user');
$table->section->range('priList,typeList,statusList,sourceList');
$table->key->range('1-10');
$table->value->range('高,中,低,urgent,normal');
$table->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$customTest = new customTest();

// 5. 测试步骤
r($customTest->checkEmptyKeysTest(array('1', '2', '3'), array('高', '中', '低'), array('0', '0', '0'), 'story', 'priList', 'zh-cn')) && p() && e('1'); // 步骤1：有效的key和value组合
r($customTest->checkEmptyKeysTest(array('1', '2'), array('高', ''), array('0', '0'), 'story', 'priList', 'zh-cn')) && p() && e('value_empty_error'); // 步骤2：key存在但value为空字符串
r($customTest->checkEmptyKeysTest(array('', '2'), array('', '中'), array('0', '0'), 'story', 'priList', 'zh-cn')) && p() && e('1'); // 步骤3：key为空的情况
r($customTest->checkEmptyKeysTest(array('', '', '3'), array('', '', '低'), array('0', '0', '0'), 'story', 'priList', 'zh-cn')) && p() && e('1'); // 步骤4：多个空key处理逻辑
r($customTest->checkEmptyKeysTest(array('1', '2', '3'), array('高', '', '低'), array('0', '0', '0'), 'bug', 'severityList', 'zh-cn')) && p() && e('value_empty_error'); // 步骤5：混合有效和无效数据测试