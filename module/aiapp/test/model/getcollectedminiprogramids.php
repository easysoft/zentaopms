#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::getCollectedMiniProgramIDs();
timeout=0
cid=0

- 步骤1：用户ID为1的收藏列表
 -  @1003
 - 属性1 @1002
 - 属性2 @1001
- 步骤2：不存在用户 @0
- 步骤3：用户ID为0 @0
- 步骤4：负数用户ID @0
- 步骤5：非数字用户ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogramstar');
$table->id->range('1-10');
$table->appID->range('1001,1002,1003,1004,1005');
$table->userID->range('1{3},2{2}');
$table->createdDate->range('`2024-01-01 00:00:00`,`2024-02-01 10:30:00`,`2024-03-01 15:45:00`,`2024-04-01 09:20:00`,`2024-05-01 14:10:00`');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiappTest = new aiappModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiappTest->getCollectedMiniProgramIDsTest('1')) && p('0,1,2') && e('1003,1002,1001'); // 步骤1：用户ID为1的收藏列表
r($aiappTest->getCollectedMiniProgramIDsTest('999')) && p() && e('0'); // 步骤2：不存在用户
r($aiappTest->getCollectedMiniProgramIDsTest('0')) && p() && e('0'); // 步骤3：用户ID为0
r($aiappTest->getCollectedMiniProgramIDsTest('-1')) && p() && e('0'); // 步骤4：负数用户ID
r($aiappTest->getCollectedMiniProgramIDsTest('abc')) && p() && e('0'); // 步骤5：非数字用户ID