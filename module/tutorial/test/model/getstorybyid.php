#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getStoryByID();
timeout=0
cid=19476

- 步骤1：获取业务需求
 - 属性id @1
 - 属性type @epic
 - 属性title @Test epic
- 步骤2：获取用户需求
 - 属性id @2
 - 属性type @requirement
 - 属性title @Test requirement
- 步骤3：获取研发需求
 - 属性id @3
 - 属性type @story
 - 属性title @Test active story
- 步骤4：获取不存在的需求
 - 属性id @3
 - 属性type @story
 - 属性title @Test active story
- 步骤5：获取边界值需求
 - 属性id @3
 - 属性type @story
 - 属性title @Test active story

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getStoryByIDTest(1)) && p('id,type,title') && e('1,epic,Test epic'); // 步骤1：获取业务需求
r($tutorialTest->getStoryByIDTest(2)) && p('id,type,title') && e('2,requirement,Test requirement'); // 步骤2：获取用户需求
r($tutorialTest->getStoryByIDTest(3)) && p('id,type,title') && e('3,story,Test active story'); // 步骤3：获取研发需求
r($tutorialTest->getStoryByIDTest(999)) && p('id,type,title') && e('3,story,Test active story'); // 步骤4：获取不存在的需求
r($tutorialTest->getStoryByIDTest(0)) && p('id,type,title') && e('3,story,Test active story'); // 步骤5：获取边界值需求