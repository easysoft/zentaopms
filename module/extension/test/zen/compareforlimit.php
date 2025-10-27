#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::compareForLimit();
timeout=0
cid=0

- 步骤1：正常版本范围内 @1
- 步骤2：版本超出最大限制 @0
- 步骤3：版本低于最小但小于最大限制 @1
- 步骤4：noBetween模式 @0
- 步骤5：空限制参数返回true @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->compareForLimitTest('1.5.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('1'); // 步骤1：正常版本范围内
r($extensionTest->compareForLimitTest('2.1.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('0'); // 步骤2：版本超出最大限制
r($extensionTest->compareForLimitTest('0.9.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('1'); // 步骤3：版本低于最小但小于最大限制
r($extensionTest->compareForLimitTest('1.5.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'noBetween')) && p() && e('0'); // 步骤4：noBetween模式
r($extensionTest->compareForLimitTest('1.5.0', array(), 'between')) && p() && e('1'); // 步骤5：空限制参数返回true