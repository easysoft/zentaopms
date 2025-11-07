#!/usr/bin/env php
<?php

/**

title=测试 companyZen::buildDyanmicSearchForm();
timeout=0
cid=0

- 步骤1:传入有效用户ID 1 @admin
- 步骤2:传入用户ID 0 @all
- 步骤3:传入空数组,用户ID 1 @admin
- 步骤4:传入有数据的数组和用户ID 2 @user1
- 步骤5:传入queryID为10 @admin

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);
zenData('product')->gen(5);
zenData('project')->gen(5);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$companyTest = new companyZenTest();

// 5. 准备测试数据
$products = array(1 => '产品1', 2 => '产品2');
$projects = array(1 => '项目1', 2 => '项目2');
$executions = array(1 => '执行1', 2 => '执行2');

// 6. 强制要求:必须包含至少5个测试步骤
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 1, 0)) && p() && e('admin'); // 步骤1:传入有效用户ID 1
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 0, 0)) && p() && e('all'); // 步骤2:传入用户ID 0
r($companyTest->buildDyanmicSearchFormTest(array(), array(), array(), 1, 0)) && p() && e('admin'); // 步骤3:传入空数组,用户ID 1
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 2, 5)) && p() && e('user1'); // 步骤4:传入有数据的数组和用户ID 2
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 1, 10)) && p() && e('admin'); // 步骤5:传入queryID为10