#!/usr/bin/env php
<?php

/**

title=测试 aiModel::isExecutable();
timeout=0
cid=15056

- 步骤1：完整有效的prompt对象 @1
- 步骤2：缺少必填字段name的prompt对象 @0
- 步骤3：module字段为空字符串的prompt对象 @0
- 步骤4：source字段为空字符串的prompt对象 @0
- 步骤5：purpose字段为空字符串的prompt对象 @0
- 步骤6：targetForm字段为空字符串的prompt对象 @0
- 步骤7：传入空值null @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（简化处理，不依赖数据库数据）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->isExecutableTest((object)array('id' => 1, 'name' => 'prompt1', 'module' => 'story', 'source' => ',story.title,story.spec,', 'purpose' => 'test purpose1', 'targetForm' => 'story.create'))) && p() && e('1'); // 步骤1：完整有效的prompt对象
r($aiTest->isExecutableTest((object)array('id' => 2, 'name' => '', 'module' => 'story', 'source' => ',story.title,story.spec,', 'purpose' => 'test purpose2', 'targetForm' => 'story.create'))) && p() && e('0'); // 步骤2：缺少必填字段name的prompt对象
r($aiTest->isExecutableTest((object)array('id' => 3, 'name' => 'prompt3', 'module' => '', 'source' => ',story.title,story.spec,', 'purpose' => 'test purpose3', 'targetForm' => 'story.create'))) && p() && e('0'); // 步骤3：module字段为空字符串的prompt对象
r($aiTest->isExecutableTest((object)array('id' => 4, 'name' => 'prompt4', 'module' => 'story', 'source' => '', 'purpose' => 'test purpose4', 'targetForm' => 'story.create'))) && p() && e('0'); // 步骤4：source字段为空字符串的prompt对象
r($aiTest->isExecutableTest((object)array('id' => 5, 'name' => 'prompt5', 'module' => 'story', 'source' => ',story.title,story.spec,', 'purpose' => '', 'targetForm' => 'story.create'))) && p() && e('0'); // 步骤5：purpose字段为空字符串的prompt对象
r($aiTest->isExecutableTest((object)array('id' => 6, 'name' => 'prompt6', 'module' => 'story', 'source' => ',story.title,story.spec,', 'purpose' => 'test purpose6', 'targetForm' => ''))) && p() && e('0'); // 步骤6：targetForm字段为空字符串的prompt对象
r($aiTest->isExecutableTest(null)) && p() && e('0'); // 步骤7：传入空值null