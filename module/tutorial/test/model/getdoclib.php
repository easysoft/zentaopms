#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDocLib();
timeout=0
cid=19424

- 步骤1：正常调用getDocLib方法
 - 属性id @2
 - 属性name @Test Doc Lib
 - 属性type @custom
- 步骤2：验证文档库ID属性属性id @2
- 步骤3：验证文档库名称属性属性name @Test Doc Lib
- 步骤4：验证文档库类型属性属性type @custom
- 步骤5：验证文档库创建者属性属性addedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getDocLibTest()) && p('id,name,type') && e('2,Test Doc Lib,custom'); // 步骤1：正常调用getDocLib方法
r($tutorialTest->getDocLibTest()) && p('id') && e('2'); // 步骤2：验证文档库ID属性
r($tutorialTest->getDocLibTest()) && p('name') && e('Test Doc Lib'); // 步骤3：验证文档库名称属性
r($tutorialTest->getDocLibTest()) && p('type') && e('custom'); // 步骤4：验证文档库类型属性
r($tutorialTest->getDocLibTest()) && p('addedBy') && e('admin'); // 步骤5：验证文档库创建者属性