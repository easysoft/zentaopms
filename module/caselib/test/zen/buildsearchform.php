#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::buildSearchForm();
timeout=0
cid=15543

- 步骤1：正常情况属性module @testcase
- 步骤2：lib字段第fields条的lib属性 @所属库
- 步骤3：标题字段第fields条的title属性 @用例名称
- 步骤4：其他相关字段
 - 第fields条的type属性 @用例类型
 - 第fields条的keywords属性 @关键词
 - 第fields条的status属性 @用例状态
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
r($caselibTest->buildSearchFormTest(1, array(1 => '测试用例库1'), 10, 'http://test.com/search')) && p('module') && e('testcase'); // 步骤1：正常情况
r($caselibTest->buildSearchFormTest(1, array(1 => '测试用例库1'), 0, '')) && p('fields:lib') && e('所属库'); // 步骤2：lib字段
r($caselibTest->buildSearchFormTest(2, array(2 => '测试用例库2'), 5, 'test.php')) && p('fields:title') && e('用例名称'); // 步骤3：标题字段
r($caselibTest->buildSearchFormTest(1, array(1 => '库1'), 1, 'search.php')) && p('fields:type,keywords,status') && e('用例类型,关键词,用例状态'); // 步骤4：其他相关字段
r($caselibTest->buildSearchFormTest(3, array(3 => '用例库3'), 99, 'http://example.com/action')) && p('queryID,actionURL') && e('99,http://example.com/action'); // 步骤5：queryID和actionURL设置
