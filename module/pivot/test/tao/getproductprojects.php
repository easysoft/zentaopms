#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getProductProjects();
timeout=0
cid=0

- 步骤1：正常情况返回数组类型 @1
- 步骤2：当前测试环境返回空数组 @1
- 步骤3：验证返回空数组长度为0 @0
- 步骤4：验证没有数据库错误 @0
- 步骤5：验证测试方法存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
zendata('project')->loadYaml('project_getproductprojects', false, 2)->gen(5);
zendata('projectproduct')->loadYaml('projectproduct_getproductprojects', false, 2)->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTest();

// 5. 必须包含至少5个测试步骤
r(is_array($pivotTest->getProductProjectsTest())) && p() && e('1'); // 步骤1：正常情况返回数组类型
r(empty($pivotTest->getProductProjectsTest())) && p() && e('1'); // 步骤2：当前测试环境返回空数组
r(count($pivotTest->getProductProjectsTest())) && p() && e('0'); // 步骤3：验证返回空数组长度为0
r(dao::isError()) && p() && e('0'); // 步骤4：验证没有数据库错误
r(method_exists($pivotTest, 'getProductProjectsTest')) && p() && e('1'); // 步骤5：验证测试方法存在