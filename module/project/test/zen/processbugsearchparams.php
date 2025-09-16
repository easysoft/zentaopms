#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processBugSearchParams();
timeout=0
cid=0

- 执行projectTest模块的processBugSearchParamsTest方法，参数是$projectObj1, 'all', 0, 1, 1, '0', 'id_desc', 0, $products1 属性removedFields @project
- 执行$result2['beforeFieldsCount'] > $result2['afterFieldsCount'] @1
- 执行projectTest模块的processBugSearchParamsTest方法，参数是$projectObj3, 'bymodule', 5, 3, 3, '1', 'status_asc', 1, $products3 属性removedFields @project
- 执行projectTest模块的processBugSearchParamsTest方法，参数是$projectObj4, 'assignedto', 10, 4, 0, '', 'openedDate_desc', 0, $products4
 - 属性removedFields @product
- 执行projectTest模块的processBugSearchParamsTest方法，参数是$projectObj5, 'bysearch', 15, 5, 5, '0', 'id_asc', 2, $products5 属性queryID @15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-20');
$project->name->range('项目1,项目2,产品项目1,产品项目2,无产品项目1,无产品项目2,Scrum项目1,Scrum项目2,瀑布项目1,瀑布项目2');
$project->hasProduct->range('1{10},0{10}');
$project->model->range('scrum{5},kanban{5},waterfall{5},waterfallplus{5}');
$project->multiple->range('1{12},0{8}');
$project->type->range('project');
$project->status->range('wait{5},doing{10},suspended{3},closed{2}');
$project->gen(20);

$product = zenData('product');
$product->id->range('1-15');
$product->name->range('产品A1,产品A2,产品B1,产品B2,多分支产品1,多分支产品2,普通产品1,普通产品2,普通产品3,普通产品4,普通产品5');
$product->type->range('normal{10},branch{3},platform{2}');
$product->status->range('normal{12},closed{3}');
$product->shadow->range('0{12},1{3}');
$product->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：测试有产品关联的Scrum项目 - 应该只移除project字段
$projectObj1 = new stdClass();
$projectObj1->hasProduct = 1;
$projectObj1->model = 'scrum';
$projectObj1->multiple = 1;
$products1 = array(1 => 'product1', 2 => 'product2');
r($projectTest->processBugSearchParamsTest($projectObj1, 'all', 0, 1, 1, '0', 'id_desc', 0, $products1)) && p('removedFields') && e('project');

// 步骤2：测试无产品关联的非Scrum项目 - 应该移除product、plan、project字段，剩余字段数量应该更少
$projectObj2 = new stdClass();
$projectObj2->hasProduct = 0;
$projectObj2->model = 'waterfall';
$projectObj2->multiple = 0;
$products2 = array();
$result2 = $projectTest->processBugSearchParamsTest($projectObj2, 'all', 0, 2, 0, '', 'id_desc', 0, $products2);
r($result2['beforeFieldsCount'] > $result2['afterFieldsCount']) && p() && e('1');

// 步骤3：测试多迭代有产品项目 - 应该只移除project字段
$projectObj3 = new stdClass();
$projectObj3->hasProduct = 1;
$projectObj3->model = 'kanban';
$projectObj3->multiple = 1;
$products3 = array(3 => 'product3');
r($projectTest->processBugSearchParamsTest($projectObj3, 'bymodule', 5, 3, 3, '1', 'status_asc', 1, $products3)) && p('removedFields') && e('project');

// 步骤4：测试单迭代无产品项目中Scrum模型 - 应该移除product、plan、project字段
$projectObj4 = new stdClass();
$projectObj4->hasProduct = 0;
$projectObj4->model = 'scrum';
$projectObj4->multiple = 0;
$products4 = array();
r($projectTest->processBugSearchParamsTest($projectObj4, 'assignedto', 10, 4, 0, '', 'openedDate_desc', 0, $products4)) && p('removedFields') && e('product,plan,project');

// 步骤5：测试bysearch类型参数设置queryID
$projectObj5 = new stdClass();
$projectObj5->hasProduct = 1;
$projectObj5->model = 'scrum';
$projectObj5->multiple = 1;
$products5 = array(5 => 'product5');
r($projectTest->processBugSearchParamsTest($projectObj5, 'bysearch', 15, 5, 5, '0', 'id_asc', 2, $products5)) && p('queryID') && e('15');