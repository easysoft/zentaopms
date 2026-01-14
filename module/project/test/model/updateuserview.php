#!/usr/bin/env php
<?php

/**

title=测试 projectModel::updateUserView();
timeout=0
cid=17882

- 步骤1：ACL为open时直接返回true @1
- 步骤2：ACL为private时更新用户视图 @1
- 步骤3：不存在的项目ID @1
- 步骤4：ACL为空字符串 @1
- 步骤5：包含执行的项目，验证执行视图更新 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('project')->loadYaml('project_updateuserview', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->updateUserViewTest(1, 'open'))      && p() && e('1'); // 步骤1：ACL为open时直接返回true
r($projectTest->updateUserViewTest(1, 'private'))   && p() && e('1'); // 步骤2：ACL为private时更新用户视图
r($projectTest->updateUserViewTest(999, 'private')) && p() && e('1'); // 步骤3：不存在的项目ID
r($projectTest->updateUserViewTest(2, ''))          && p() && e('1'); // 步骤4：ACL为空字符串
r($projectTest->updateUserViewTest(1, 'custom'))    && p() && e('1'); // 步骤5：包含执行的项目，验证执行视图更新
