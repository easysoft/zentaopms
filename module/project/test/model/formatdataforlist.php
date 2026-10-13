#!/usr/bin/env php
<?php

/**

title=测试 projectModel::formatDataForList();
timeout=0
cid=17812

- 执行projectTest模块的formatDataForListTest方法，参数是$project1, $PMList1
 - 属性budget @¥ 5万
 - 属性estimate @120h
- 属性consume @80h
- 属性surplus @40h
- 属性PM @pm1
- 属性PMName @项目经理1
- 属性PMAvatar @avatar1.jpg
- 执行projectTest模块的formatDataForListTest方法，参数是$project2, array 属性budget @待定
- 执行projectTest模块的formatDataForListTest方法，参数是$project3, $PMList3
- 属性budget @¥ 15万
- 属性PM @pm2
- 属性PMName @项目经理2
- 执行projectTest模块的formatDataForListTest方法，参数是$project4, $PMList4
- 属性budget @$ 2亿
- 属性PM @pm3
- 属性PMName @项目经理3
- 属性PMUserID @8
- 执行projectTest模块的formatDataForListTest方法，参数是$project5, $PMList5
- 属性budget @€ 8万
- 属性PM @pm4
- 属性PMName @项目经理4
- 属性PMAvatar @avatar4.jpg
 - 属性PMUserID @9
- 执行projectTest模块的formatDataForListTest方法，参数是$project6, $PMList6
- 属性budget @¥ 3万
- 属性end @2030-12-31
- 属性PM @pm5
- 属性PMName @项目经理5
- 执行projectTest模块的formatDataForListTest方法，参数是$project7, array 属性budget @¥ 2.5万

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,pm1,pm2,pm3,pm4,pm5');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,项目经理1,项目经理2,项目经理3,项目经理4,项目经理5');
$user->avatar->range('avatar1.jpg,avatar2.jpg,avatar3.jpg,avatar4.jpg,avatar5.jpg');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：正常项目对象格式化
$project1 = new stdClass();
$project1->budget = '50000';
$project1->budgetUnit = 'CNY';
$project1->estimate = '120';
$project1->consumed = '80';
$project1->left = '40';
$project1->progress = '66.67';
$project1->end = '2024-12-31';
$project1->PM = 'pm1';
$project1->status = 'doing';
$PMList1 = array('pm1' => (object)array('id' => 6, 'realname' => '项目经理1', 'avatar' => 'avatar1.jpg'));
r($projectTest->formatDataForListTest($project1, $PMList1)) && p('budget,estimate,consume,surplus,PM,PMName,PMAvatar') && e('¥ 5万,120h,80h,40h,pm1,项目经理1,avatar1.jpg');

// 测试步骤2：预算为0的项目格式化
$project2 = new stdClass();
$project2->budget = '0';
$project2->budgetUnit = 'CNY';
$project2->estimate = '100';
$project2->consumed = '50';
$project2->left = '50';
$project2->progress = '50.00';
$project2->end = '2024-11-30';
$project2->PM = '';
$project2->status = 'wait';
r($projectTest->formatDataForListTest($project2, array())) && p('budget') && e('待定');

// 测试步骤3：大额预算项目格式化(万元)
$project3 = new stdClass();
$project3->budget = '150000';
$project3->budgetUnit = 'CNY';
$project3->estimate = '200';
$project3->consumed = '100';
$project3->left = '100';
$project3->progress = '50.00';
$project3->end = '2025-03-31';
$project3->PM = 'pm2';
$project3->status = 'doing';
$PMList3 = array('pm2' => (object)array('id' => 7, 'realname' => '项目经理2', 'avatar' => 'avatar2.jpg'));
r($projectTest->formatDataForListTest($project3, $PMList3)) && p('budget,PM,PMName') && e('¥ 15万,pm2,项目经理2');

// 测试步骤4：大额预算项目格式化(亿元)
$project4 = new stdClass();
$project4->budget = '200000000';
$project4->budgetUnit = 'USD';
$project4->estimate = '500';
$project4->consumed = '300';
$project4->left = '200';
$project4->progress = '60.00';
$project4->end = '2025-12-31';
$project4->PM = 'pm3';
$project4->status = 'doing';
$PMList4 = array('pm3' => (object)array('id' => 8, 'realname' => '项目经理3', 'avatar' => 'avatar3.jpg'));
r($projectTest->formatDataForListTest($project4, $PMList4)) && p('budget,PM,PMName,PMUserID') && e('$ 2亿,pm3,项目经理3,8');

// 测试步骤5：带项目经理信息的项目格式化
$project5 = new stdClass();
$project5->budget = '80000';
$project5->budgetUnit = 'EUR';
$project5->estimate = '160';
$project5->consumed = '120';
$project5->left = '40';
$project5->progress = '75.00';
$project5->end = '2024-10-31';
$project5->PM = 'pm4';
$project5->status = 'doing';
$PMList5 = array('pm4' => (object)array('id' => 9, 'realname' => '项目经理4', 'avatar' => 'avatar4.jpg'));
r($projectTest->formatDataForListTest($project5, $PMList5)) && p('budget,PM,PMName,PMAvatar,PMUserID') && e('€ 8万,pm4,项目经理4,avatar4.jpg,9');

// 测试步骤6：长期项目结束时间格式化
$project6 = new stdClass();
$project6->budget = '30000';
$project6->budgetUnit = 'CNY';
$project6->estimate = '80';
$project6->consumed = '40';
$project6->left = '40';
$project6->progress = '50.00';
$project6->end = '2030-12-31';
$project6->PM = 'pm5';
$project6->status = 'doing';
$PMList6 = array('pm5' => (object)array('id' => 10, 'realname' => '项目经理5', 'avatar' => 'avatar5.jpg'));
r($projectTest->formatDataForListTest($project6, $PMList6)) && p('budget,end,PM,PMName') && e('¥ 3万,2030-12-31,pm5,项目经理5');

// 测试步骤7：空项目经理的项目格式化
$project7 = new stdClass();
$project7->budget = '25000';
$project7->budgetUnit = 'CNY';
$project7->estimate = '60';
$project7->consumed = '30';
$project7->left = '30';
$project7->progress = '50.00';
$project7->end = '2024-09-30';
$project7->PM = '';
$project7->status = 'wait';
r($projectTest->formatDataForListTest($project7, array())) && p('budget') && e('¥ 2.5万');
