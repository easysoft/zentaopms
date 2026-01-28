#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getGradeList();
timeout=0
cid=18534

- 步骤1：获取默认类型需求等级列表第0条的type属性 @story
- 步骤2：获取story类型需求等级列表第0条的type属性 @story
- 步骤3：获取requirement类型需求等级列表第0条的type属性 @requirement
- 步骤4：获取epic类型需求等级列表第0条的type属性 @epic
- 步骤5：获取所有类型需求等级列表 @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('storygrade')->loadYaml('storygrade_getgradelist', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getGradeListTest()) && p('0:type') && e('story'); // 步骤1：获取默认类型需求等级列表
r($storyTest->getGradeListTest('story')) && p('0:type') && e('story'); // 步骤2：获取story类型需求等级列表
r($storyTest->getGradeListTest('requirement')) && p('0:type') && e('requirement'); // 步骤3：获取requirement类型需求等级列表
r($storyTest->getGradeListTest('epic')) && p('0:type') && e('epic'); // 步骤4：获取epic类型需求等级列表
r(count($storyTest->getGradeListTest(''))) && p() && e('10'); // 步骤5：获取所有类型需求等级列表