#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::buildSearchForm();
timeout=0
cid=15543

- 步骤1：正常情况属性module @caselib
- 步骤2：lib字段配置属性libAllValue @所有用例库
- 步骤3：移除product字段属性hasProduct @0
- 步骤4：移除运行相关字段
 - 属性hasLastRunner @0
 - 属性hasLastRunResult @0
 - 属性hasLastRunDate @0
- 步骤5：queryID和actionURL设置
 - 属性queryID @99
 - 属性actionURL @http://example.com/action

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testsuite');
$table->name->range('测试用例库{1-10}');
$table->type->range('caselib');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($caselibTest->buildSearchFormTest(1, array(1 => '测试用例库1'), 10, 'http://test.com/search')) && p('module') && e('caselib'); // 步骤1：正常情况
r($caselibTest->buildSearchFormTest(1, array(1 => '测试用例库1'), 0, '')) && p('libAllValue') && e('所有用例库'); // 步骤2：lib字段配置
r($caselibTest->buildSearchFormTest(2, array(2 => '测试用例库2'), 5, 'test.php')) && p('hasProduct') && e('0'); // 步骤3：移除product字段
r($caselibTest->buildSearchFormTest(1, array(1 => '库1'), 1, 'search.php')) && p('hasLastRunner,hasLastRunResult,hasLastRunDate') && e('0,0,0'); // 步骤4：移除运行相关字段
r($caselibTest->buildSearchFormTest(3, array(3 => '用例库3'), 99, 'http://example.com/action')) && p('queryID,actionURL') && e('99,http://example.com/action'); // 步骤5：queryID和actionURL设置