#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildCustomFieldValueData();
timeout=0
cid=15808

- 步骤1：正常情况属性id @1001
- 步骤2：缺少stringvalue属性stringvalue @~~
- 步骤3：缺少date值属性datevalue @~~
- 步骤4：缺少number值属性numbervalue @~~
- 步骤5：验证字符串值属性stringvalue @test value

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$convertTest = new convertTaoTest();

// 4. 测试步骤
r($convertTest->buildCustomFieldValueDataTest(array('id' => '1001', 'issue' => '2001', 'customfield' => '3001', 'stringvalue' => 'test string', 'datevalue' => '2023-12-01', 'numbervalue' => '123.45'))) && p('id') && e('1001'); // 步骤1：正常情况
r($convertTest->buildCustomFieldValueDataTest(array('id' => '1002', 'issue' => '2002', 'customfield' => '3002', 'datevalue' => '2023-12-02', 'numbervalue' => '678.90'))) && p('stringvalue') && e('~~'); // 步骤2：缺少stringvalue
r($convertTest->buildCustomFieldValueDataTest(array('id' => '1003', 'issue' => '2003', 'customfield' => '3003', 'stringvalue' => 'only string'))) && p('datevalue') && e('~~'); // 步骤3：缺少date值
r($convertTest->buildCustomFieldValueDataTest(array('id' => '1004', 'issue' => '2004', 'customfield' => '3004'))) && p('numbervalue') && e('~~'); // 步骤4：缺少number值
r($convertTest->buildCustomFieldValueDataTest(array('id' => '1005', 'issue' => '2005', 'customfield' => '3005', 'stringvalue' => 'test value'))) && p('stringvalue') && e('test value'); // 步骤5：验证字符串值