#!/usr/bin/env php
<?php

/**

title=测试 projectstoryModel::buildSearchConfig();
timeout=0
cid=17977

- 步骤1：正常项目ID返回配置数组属性module @story
- 步骤2：另一个正常项目ID属性module @story
- 步骤3：第三个项目ID属性module @story
- 步骤4：验证不存在项目的处理属性module @story
- 步骤5：验证无效项目ID的处理属性module @story

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('project')->gen(10);
zenData('product')->gen(5);
zenData('projectproduct')->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectstoryTest = new projectstoryModelTest();

// 5. 执行测试步骤（必须包含至少5个测试步骤）
r($projectstoryTest->buildSearchConfigTest(1)) && p('module') && e('story'); // 步骤1：正常项目ID返回配置数组
r($projectstoryTest->buildSearchConfigTest(2)) && p('module') && e('story'); // 步骤2：另一个正常项目ID
r($projectstoryTest->buildSearchConfigTest(3)) && p('module') && e('story'); // 步骤3：第三个项目ID
r($projectstoryTest->buildSearchConfigTest(999)) && p('module') && e('story'); // 步骤4：验证不存在项目的处理
r($projectstoryTest->buildSearchConfigTest(0)) && p('module') && e('story'); // 步骤5：验证无效项目ID的处理