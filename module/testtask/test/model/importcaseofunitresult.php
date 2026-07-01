#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::importCaseOfUnitResult();
timeout=0
cid=19198

- 步骤1：更新已有用例 @1
- 步骤2：创建新用例 @6
- 步骤3：返回已存在用例ID @3
- 步骤4：创建带步骤的用例 @7
- 步骤5：测试空existCases @8

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-5');
$case->title->range('测试用例1,测试用例2,已存在用例,单元测试用例,功能测试用例');
$case->product->range('1');
$case->module->range('1');
$case->type->range('unit');
$case->auto->range('unit');
$case->status->range('normal');
$case->version->range('1');
$case->openedBy->range('admin');
$case->openedDate->range('`2024-01-01 10:00:00`');
$case->gen(5);

zenData('webhook')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest  = new testtaskModelTest();
$testtaskModel = $testtaskTest->instance;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$cases = [
    (object)['id' => 1, 'title' => '更新测试用例', 'type' => 'unit', 'auto' => 'unit', 'product' => 1],
    (object)['title' => '新建单元测试用例', 'type' => 'unit', 'auto' => 'unit', 'product' => 1, 'module' => 1, 'openedBy' => 'admin', 'openedDate' => '2024-01-01 10:00:00', 'status' => 'normal', 'version' => 1],
    (object)['title' => '测试用例1', 'type' => 'unit', 'auto' => 'unit', 'product' => 1],
    (object)['title' => '带步骤的新用例', 'type' => 'unit', 'auto' => 'unit', 'product' => 1, 'module' => 1, 'openedBy' => 'admin', 'openedDate' => '2024-01-01 10:00:00', 'status' => 'normal', 'version' => 1, 'steps' => [(object)['desc' => '测试步骤1', 'expect' => '预期结果1'], (object)['desc' => '测试步骤2', 'expect' => '预期结果2']]],
    (object)['title' => '空existCases测试', 'type' => 'unit', 'auto' => 'unit', 'product' => 1, 'module' => 1, 'openedBy' => 'admin', 'openedDate' => '2024-01-01 10:00:00', 'status' => 'normal', 'version' => 1]
];

$testtaskModel->loadModel('action');

$reflection = new ReflectionClass($testtaskModel);
$method = $reflection->getMethod('importCaseOfUnitResult');
$method->setAccessible(true);

r($method->invokeArgs($testtaskModel, [&$cases[0], []]))                 && p('0') && e('1'); // 步骤1：更新已有用例
r($method->invokeArgs($testtaskModel, [&$cases[1], []]))                 && p('0') && e('6'); // 步骤2：创建新用例
r($method->invokeArgs($testtaskModel, [&$cases[2], ['测试用例1' => 3]])) && p('0') && e('3'); // 步骤3：返回已存在用例ID
r($method->invokeArgs($testtaskModel, [&$cases[3], []]))                 && p('0') && e('7'); // 步骤4：创建带步骤的用例
r($method->invokeArgs($testtaskModel, [&$cases[4], []]))                 && p('0') && e('8'); // 步骤5：测试空existCases
