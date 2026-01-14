#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getStory();
timeout=0
cid=19475

- 步骤1：正常获取研发需求，验证ID属性id @3
- 步骤2：验证标题属性属性title @Test active story
- 步骤3：验证状态属性属性status @active
- 步骤4：验证类型属性属性type @story
- 步骤5：验证优先级和估时属性
 - 属性pri @3
 - 属性estimate @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getStoryTest()) && p('id') && e('3');                          // 步骤1：正常获取研发需求，验证ID
r($tutorialTest->getStoryTest()) && p('title') && e('Test active story');       // 步骤2：验证标题属性
r($tutorialTest->getStoryTest()) && p('status') && e('active');                 // 步骤3：验证状态属性
r($tutorialTest->getStoryTest()) && p('type') && e('story');                    // 步骤4：验证类型属性
r($tutorialTest->getStoryTest()) && p('pri,estimate') && e('3,1');              // 步骤5：验证优先级和估时属性