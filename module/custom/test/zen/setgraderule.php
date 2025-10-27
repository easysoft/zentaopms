#!/usr/bin/env php
<?php

/**

title=测试 customZen::setGradeRule();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：不同模块 @1
- 步骤3：不同模块 @1
- 步骤4：空数据情况 @1
- 步骤5：复杂数据情况 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 不需要特殊数据准备，使用默认数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$customTest = new customTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($customTest->setGradeRuleTest('story', array('gradeRule' => 'normal'))) && p() && e('1'); // 步骤1：正常情况
r($customTest->setGradeRuleTest('requirement', array('gradeRule' => 'requirement'))) && p() && e('1'); // 步骤2：不同模块
r($customTest->setGradeRuleTest('epic', array('gradeRule' => 'epic'))) && p() && e('1'); // 步骤3：不同模块
r($customTest->setGradeRuleTest('story', array())) && p() && e('1'); // 步骤4：空数据情况
r($customTest->setGradeRuleTest('story', array('gradeRule' => 'complex', 'otherField' => 'value'))) && p() && e('1'); // 步骤5：复杂数据情况