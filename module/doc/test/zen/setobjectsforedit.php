#!/usr/bin/env php
<?php

/**

title=测试 docZen::setObjectsForEdit();
timeout=0
cid=0

- 步骤1：项目类型 @1
- 步骤2：执行类型 @0
- 步骤3：产品类型 @10
- 步骤4：我的空间类型 @0
- 步骤5：无效类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->loadYaml('zt_project_setobjectsforedit', false, 2)->gen(10);

$execution = zenData('project');
$execution->loadYaml('zt_execution_setobjectsforedit', false, 2)->gen(15);

$product = zenData('product');
$product->loadYaml('zt_product_setobjectsforedit', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->setObjectsForEditTest('project', 1)) && p() && e('1'); // 步骤1：项目类型
r($docTest->setObjectsForEditTest('execution', 1)) && p() && e('0'); // 步骤2：执行类型
r($docTest->setObjectsForEditTest('product', 1)) && p() && e('10'); // 步骤3：产品类型
r($docTest->setObjectsForEditTest('mine', 1)) && p() && e('0'); // 步骤4：我的空间类型
r($docTest->setObjectsForEditTest('invalid', 1)) && p() && e('0'); // 步骤5：无效类型