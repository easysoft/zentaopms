#!/usr/bin/env php
<?php

/**

title=测试 actionZen::getTrashesHeaderNavigation();
timeout=0
cid=14971

- 空数组应该返回0个元素 @0
- 应该返回3个首选类型 @3
- 非首选类型也应该被返回 @3
- 无效类型应该被过滤 @0
- story应该在首选类型中 @1
- 默认不超过10个首选类型 @1
- light模式应该返回至少1个类型 @1
- 应该从非首选类型中补充 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
// 该方法不需要数据表数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：测试空对象类型列表
r($actionTest->getTrashesHeaderNavigationTest(array())) && p() && e('0'); // 空数组应该返回0个元素

// 步骤2：测试包含首选类型的对象类型列表 - ALM模式
global $tester;
$originalSystemMode = $tester->config->systemMode;
$tester->config->systemMode = 'ALM';
$preferredTypes = array('story', 'task', 'bug');
r(count($actionTest->getTrashesHeaderNavigationTest($preferredTypes))) && p() && e('3'); // 应该返回3个首选类型

// 步骤3：测试包含非首选类型的对象类型列表
$nonPreferredTypes = array('release', 'testsuite', 'testreport');
r(count($actionTest->getTrashesHeaderNavigationTest($nonPreferredTypes))) && p() && e('3'); // 非首选类型也应该被返回

// 步骤4：测试不包含有效对象表的类型列表
$invalidTypes = array('invalid1', 'invalid2', 'invalid3');
r(count($actionTest->getTrashesHeaderNavigationTest($invalidTypes))) && p() && e('0'); // 无效类型应该被过滤

// 步骤5：测试混合首选和非首选类型
$mixedTypes = array('story', 'task', 'bug', 'release', 'testsuite', 'testreport');
$result = $actionTest->getTrashesHeaderNavigationTest($mixedTypes);
r(isset($result['story'])) && p() && e('1'); // story应该在首选类型中

// 步骤6：测试超过首选数量限制的类型列表
$manyPreferredTypes = array('story', 'task', 'bug', 'productplan', 'release', 'build', 'testtask', 'testcase', 'doc', 'testsuite', 'testreport', 'requirement');
$result = $actionTest->getTrashesHeaderNavigationTest($manyPreferredTypes);
r(count($result) <= 10) && p() && e('1'); // 默认不超过10个首选类型

// 步骤7：测试light模式下的对象类型列表
$tester->config->systemMode = 'light';
$lightTypes = array('story', 'task', 'bug', 'doc');
r(count($actionTest->getTrashesHeaderNavigationTest($lightTypes)) >= 1) && p() && e('1'); // light模式应该返回至少1个类型

// 步骤8：测试首选类型不足时的补充逻辑
$tester->config->systemMode = 'ALM';
$fewTypes = array('story', 'release', 'testsuite'); // 只有一个首选类型story，其他两个不是首选类型
$result = $actionTest->getTrashesHeaderNavigationTest($fewTypes);
r(count($result) == 3) && p() && e('1'); // 应该从非首选类型中补充

// 恢复原始配置
$tester->config->systemMode = $originalSystemMode;