#!/usr/bin/env php
<?php

/**

title=测试 storyTao::buildStoryTree();
timeout=0
cid=18604

- 步骤1：空数组输入 @0
- 步骤2：简单父子关系 @3
- 步骤3：多层级嵌套关系 @4
- 步骤4：无效父ID处理 @3
- 步骤5：复杂混合关系 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('story')->loadYaml('story_buildstorytree', false, 2)->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($storyTest->buildStoryTreeTest(array()))) && p() && e('0'); // 步骤1：空数组输入
r(count($storyTest->buildStoryTreeTest(array(1 => 0, 2 => 1, 3 => 1)))) && p() && e('3'); // 步骤2：简单父子关系
r(count($storyTest->buildStoryTreeTest(array(1 => 0, 2 => 1, 3 => 2, 4 => 0)))) && p() && e('4'); // 步骤3：多层级嵌套关系
r(count($storyTest->buildStoryTreeTest(array(1 => 999, 2 => 0, 3 => 999), 0, array(2 => 0)))) && p() && e('3'); // 步骤4：无效父ID处理
r(count($storyTest->buildStoryTreeTest(array(1 => 0, 2 => 1, 3 => 0, 4 => 3, 5 => 2)))) && p() && e('5'); // 步骤5：复杂混合关系