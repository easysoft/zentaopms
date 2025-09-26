#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDefaultAccount();
timeout=0
cid=0

- 步骤1：正常情况测试获取默认账号（空组件参数） @0
- 步骤2：使用mysql组件获取默认账号 @0
- 步骤3：使用redis组件获取默认账号 @0
- 步骤4：使用空字符串组件参数 @0
- 步骤5：使用无效组件名验证容错性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getDefaultAccountTest('')) && p() && e('0'); // 步骤1：正常情况测试获取默认账号（空组件参数）
r($cneTest->getDefaultAccountTest('mysql')) && p() && e('0'); // 步骤2：使用mysql组件获取默认账号
r($cneTest->getDefaultAccountTest('redis')) && p() && e('0'); // 步骤3：使用redis组件获取默认账号
r($cneTest->getDefaultAccountTest()) && p() && e('0'); // 步骤4：使用空字符串组件参数
r($cneTest->getDefaultAccountTest('invalid-component')) && p() && e('0'); // 步骤5：使用无效组件名验证容错性