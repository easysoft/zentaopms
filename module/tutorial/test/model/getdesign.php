#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDesign();
timeout=0
cid=19421

- 步骤1：验证设计ID属性id @1
- 步骤2：验证设计名称属性name @Test Design
- 步骤3：验证项目ID属性project @2
- 步骤4：验证设计类型属性type @HLDS
- 步骤5：验证描述属性desc @Design Description

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getDesignTest()) && p('id') && e('1');                       // 步骤1：验证设计ID
r($tutorialTest->getDesignTest()) && p('name') && e('Test Design');           // 步骤2：验证设计名称
r($tutorialTest->getDesignTest()) && p('project') && e('2');                  // 步骤3：验证项目ID
r($tutorialTest->getDesignTest()) && p('type') && e('HLDS');                  // 步骤4：验证设计类型
r($tutorialTest->getDesignTest()) && p('desc') && e('Design Description');    // 步骤5：验证描述