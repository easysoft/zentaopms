#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getStoryGrade();
timeout=0
cid=19477

- 步骤1：验证返回数组长度 @3
- 步骤2：验证story层级类型第0条的type属性 @story
- 步骤3：验证requirement层级类型第1条的type属性 @requirement
- 步骤4：验证epic层级类型第2条的type属性 @epic
- 步骤5：验证story层级grade为1第0条的grade属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getStoryGradeTest())) && p() && e('3'); // 步骤1：验证返回数组长度
r($tutorialTest->getStoryGradeTest()) && p('0:type') && e('story'); // 步骤2：验证story层级类型
r($tutorialTest->getStoryGradeTest()) && p('1:type') && e('requirement'); // 步骤3：验证requirement层级类型
r($tutorialTest->getStoryGradeTest()) && p('2:type') && e('epic'); // 步骤4：验证epic层级类型
r($tutorialTest->getStoryGradeTest()) && p('0:grade') && e('1'); // 步骤5：验证story层级grade为1