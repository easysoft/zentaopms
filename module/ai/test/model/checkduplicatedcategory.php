#!/usr/bin/env php
<?php

/**

title=测试 aiModel::checkDuplicatedCategory();
timeout=0
cid=15002

- 步骤1：空数据测试 @0
- 步骤2：不重复的新分类 @0
- 步骤3：包含系统预定义分类 @1
- 步骤4：数组形式的重复分类 @1
- 步骤5：多个分类中包含重复项 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($aiTest->checkDuplicatedCategoryTest(array())) && p() && e('0'); // 步骤1：空数据测试
r($aiTest->checkDuplicatedCategoryTest(array('newCategory' => '新分类', 'anotherNew' => '另一个新分类'))) && p() && e('0'); // 步骤2：不重复的新分类
r($aiTest->checkDuplicatedCategoryTest(array('category1' => '工作', 'category2' => '新分类'))) && p() && e('1'); // 步骤3：包含系统预定义分类
r($aiTest->checkDuplicatedCategoryTest(array('categories' => array('个人', '生活', '新分类')))) && p() && e('1'); // 步骤4：数组形式的重复分类
r($aiTest->checkDuplicatedCategoryTest(array('category1' => '新分类1', 'category2' => '新分类1', 'category3' => '新分类2'))) && p() && e('1'); // 步骤5：多个分类中包含重复项