#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu4QA();
timeout=0
cid=0

- 步骤1：testcase菜单处理属性testcaseSubmenuRemoved @1
- 步骤2：testsuite菜单处理属性testsuiteSubmenuRemoved @1
- 步骤3：testtask菜单处理属性testtaskSubmenuRemoved @1
- 步骤4：testreport菜单处理属性testreportSubmenuRemoved @1
- 步骤5：其他菜单处理
 - 属性qaModelLoaded @1
 - 属性moduleNameSet @1
 - 属性rawModuleSet @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->setShowErrorNoneMenu4QATest('testcase')) && p('testcaseSubmenuRemoved') && e('1'); // 步骤1：testcase菜单处理
r($productTest->setShowErrorNoneMenu4QATest('testsuite')) && p('testsuiteSubmenuRemoved') && e('1'); // 步骤2：testsuite菜单处理
r($productTest->setShowErrorNoneMenu4QATest('testtask')) && p('testtaskSubmenuRemoved') && e('1'); // 步骤3：testtask菜单处理
r($productTest->setShowErrorNoneMenu4QATest('testreport')) && p('testreportSubmenuRemoved') && e('1'); // 步骤4：testreport菜单处理
r($productTest->setShowErrorNoneMenu4QATest('bug')) && p('qaModelLoaded,moduleNameSet,rawModuleSet') && e('1,1,1'); // 步骤5：其他菜单处理