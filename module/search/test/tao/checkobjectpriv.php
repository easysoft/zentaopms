#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkObjectPriv();
timeout=0
cid=0

- 步骤1：测试产品对象权限检查，shadow产品被过滤 @3
- 步骤2：测试项目集对象权限检查，无权限项目集被过滤 @0
- 步骤3：测试项目对象权限检查，无权限项目被过滤 @0
- 步骤4：测试执行对象权限检查，无权限执行被过滤 @3
- 步骤5：测试文档对象权限检查，无权限文档被过滤 @0
- 步骤6：测试待办对象权限检查，私有待办被过滤 @3
- 步骤7：测试测试套件对象权限检查，返回原结果数组 @5
- 步骤8：测试需求对象权限检查，返回原结果数组 @5
- 步骤9：测试缺陷对象权限检查，返回原结果数组 @5
- 步骤10：测试未定义对象类型权限检查，返回原结果数组 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('product')->gen(0);
zendata('program')->gen(0);
zendata('project')->gen(0);
zendata('execution')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试数据
$testResults = array(
    1 => (object)array('id' => 1, 'title' => '测试结果1'),
    2 => (object)array('id' => 2, 'title' => '测试结果2'),
    3 => (object)array('id' => 3, 'title' => '测试结果3'),
    4 => (object)array('id' => 4, 'title' => '测试结果4'),
    5 => (object)array('id' => 5, 'title' => '测试结果5')
);

$testObjectIdList = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);

r($searchTest->checkObjectPrivTest('product', TABLE_PRODUCT, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('3'); // 步骤1：测试产品对象权限检查，shadow产品被过滤
r($searchTest->checkObjectPrivTest('program', TABLE_PROGRAM, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('0'); // 步骤2：测试项目集对象权限检查，无权限项目集被过滤
r($searchTest->checkObjectPrivTest('project', TABLE_PROJECT, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('0'); // 步骤3：测试项目对象权限检查，无权限项目被过滤
r($searchTest->checkObjectPrivTest('execution', TABLE_EXECUTION, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('3'); // 步骤4：测试执行对象权限检查，无权限执行被过滤
r($searchTest->checkObjectPrivTest('doc', TABLE_DOC, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('0'); // 步骤5：测试文档对象权限检查，无权限文档被过滤
r($searchTest->checkObjectPrivTest('todo', TABLE_TODO, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('3'); // 步骤6：测试待办对象权限检查，私有待办被过滤
r($searchTest->checkObjectPrivTest('testsuite', TABLE_TESTSUITE, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 步骤7：测试测试套件对象权限检查，返回原结果数组
r($searchTest->checkObjectPrivTest('story', TABLE_STORY, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 步骤8：测试需求对象权限检查，返回原结果数组
r($searchTest->checkObjectPrivTest('bug', TABLE_BUG, $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 步骤9：测试缺陷对象权限检查，返回原结果数组
r($searchTest->checkObjectPrivTest('unknown', '', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 步骤10：测试未定义对象类型权限检查，返回原结果数组