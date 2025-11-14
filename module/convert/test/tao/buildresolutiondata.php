#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildResolutionData();
timeout=0
cid=15825

- 步骤1：完整数据输入
 - 属性id @1
 - 属性sequence @1
 - 属性pname @Fixed
 - 属性description @Issue was resolved
- 步骤2：缺少description字段
 - 属性id @2
 - 属性sequence @2
 - 属性pname @Duplicate
 - 属性description @~~
- 步骤3：缺少description字段但有其他必需字段
 - 属性id @3
 - 属性sequence @3
 - 属性pname @WontDo
- 步骤4：验证description默认为空字符串属性description @~~
- 步骤5：包含额外字段
 - 属性id @5
 - 属性sequence @5
 - 属性pname @WontFix
 - 属性description @Not going to fix

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->buildResolutionDataTest(array('id' => 1, 'sequence' => 1, 'name' => 'Fixed', 'description' => 'Issue was resolved'))) && p('id,sequence,pname,description') && e('1,1,Fixed,Issue was resolved'); // 步骤1：完整数据输入
r($convertTest->buildResolutionDataTest(array('id' => 2, 'sequence' => 2, 'name' => 'Duplicate'))) && p('id,sequence,pname,description') && e('2,2,Duplicate,~~'); // 步骤2：缺少description字段
r($convertTest->buildResolutionDataTest(array('id' => 3, 'sequence' => 3, 'name' => 'WontDo'))) && p('id,sequence,pname') && e('3,3,WontDo'); // 步骤3：缺少description字段但有其他必需字段
r($convertTest->buildResolutionDataTest(array('id' => 4, 'sequence' => 4, 'name' => 'Incomplete'))) && p('description') && e('~~'); // 步骤4：验证description默认为空字符串
r($convertTest->buildResolutionDataTest(array('id' => 5, 'sequence' => 5, 'name' => 'WontFix', 'description' => 'Not going to fix', 'extra_field' => 'ignored'))) && p('id,sequence,pname,description') && e('5,5,WontFix,Not going to fix'); // 步骤5：包含额外字段