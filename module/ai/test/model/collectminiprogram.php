#!/usr/bin/env php
<?php

/**

title=测试 aiModel::collectMiniProgram();
timeout=0
cid=15003

- 步骤1：正常添加收藏 @1
- 步骤2：添加不同应用收藏 @1
- 步骤3：删除指定用户收藏 @1
- 步骤4：删除不存在的收藏 @1
- 步骤5：删除所有用户收藏 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogramstar');
$table->appID->range('1,2,3');
$table->userID->range('1,2,3');
$table->createdDate->range('`2023-01-01 10:00:00`');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->collectMiniProgramTest(10, 10, 'false')) && p() && e('1'); // 步骤1：正常添加收藏
r($aiTest->collectMiniProgramTest(10, 11, 'false')) && p() && e('1'); // 步骤2：添加不同应用收藏
r($aiTest->collectMiniProgramTest(10, 10, 'true')) && p() && e('1'); // 步骤3：删除指定用户收藏
r($aiTest->collectMiniProgramTest(99, 99, 'true')) && p() && e('1'); // 步骤4：删除不存在的收藏
r($aiTest->collectMiniProgramTest(null, 11, 'true')) && p() && e('1'); // 步骤5：删除所有用户收藏