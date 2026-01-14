#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getEpic();
timeout=0
cid=19428

- 步骤1：验证返回对象的ID属性属性id @1
- 步骤2：验证ID和类型
 - 属性id @1
 - 属性type @epic
- 步骤3：验证层级关系属性
 - 属性isParent @1
 - 属性root @1
- 步骤4：验证产品属性属性product @1
- 步骤5：验证标题属性属性title @Test epic

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$tutorialTest = new tutorialModelTest();

// 4. 执行测试步骤
r($tutorialTest->getEpicTest()) && p('id') && e('1'); // 步骤1：验证返回对象的ID属性
r($tutorialTest->getEpicTest()) && p('id,type') && e('1,epic'); // 步骤2：验证ID和类型
r($tutorialTest->getEpicTest()) && p('isParent,root') && e('1,1'); // 步骤3：验证层级关系属性
r($tutorialTest->getEpicTest()) && p('product') && e('1'); // 步骤4：验证产品属性
r($tutorialTest->getEpicTest()) && p('title') && e('Test epic'); // 步骤5：验证标题属性