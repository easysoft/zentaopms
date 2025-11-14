#!/usr/bin/env php
<?php

/**

title=测试 projectZen::getCopyProject();
timeout=0
cid=17942

- 步骤1：正常项目ID获取复制项目信息
 - 属性id @1
 - 属性name @项目集1
- 步骤2：另一个正常项目ID测试
 - 属性id @2
 - 属性name @项目集2
- 步骤3：第三个正常项目ID测试
 - 属性id @3
 - 属性name @项目集3
- 步骤4：第四个项目ID测试
 - 属性id @4
 - 属性name @项目集4
- 步骤5：第五个项目ID测试
 - 属性id @5
 - 属性name @项目集5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->loadYaml('project_getcopyproject', false, 2)->gen(10);

$productTable = zenData('product');
$productTable->loadYaml('product_getcopyproject', false, 2)->gen(5);

$projectProductTable = zenData('projectproduct');
$projectProductTable->loadYaml('projectproduct_getcopyproject', false, 2)->gen(10);

$productPlanTable = zenData('productplan');
$productPlanTable->loadYaml('productplan_getcopyproject', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($projectzenTest->getCopyProjectTest(1)) && p('id,name') && e('1,项目集1'); // 步骤1：正常项目ID获取复制项目信息
r($projectzenTest->getCopyProjectTest(2)) && p('id,name') && e('2,项目集2'); // 步骤2：另一个正常项目ID测试
r($projectzenTest->getCopyProjectTest(3)) && p('id,name') && e('3,项目集3'); // 步骤3：第三个正常项目ID测试
r($projectzenTest->getCopyProjectTest(4)) && p('id,name') && e('4,项目集4'); // 步骤4：第四个项目ID测试
r($projectzenTest->getCopyProjectTest(5)) && p('id,name') && e('5,项目集5'); // 步骤5：第五个项目ID测试