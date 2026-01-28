#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::isShowLastRow();
timeout=0
cid=17412

- 步骤1：测试'bottom'参数 @1
- 步骤2：测试'all'参数 @1
- 步骤3：测试'top'参数 @0
- 步骤4：测试空字符串参数 @0
- 步骤5：测试无效参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 执行5个测试步骤
r($pivotTest->isShowLastRowTest('bottom')) && p() && e('1'); // 步骤1：测试'bottom'参数
r($pivotTest->isShowLastRowTest('all')) && p() && e('1');    // 步骤2：测试'all'参数
r($pivotTest->isShowLastRowTest('top')) && p() && e('0');    // 步骤3：测试'top'参数
r($pivotTest->isShowLastRowTest('')) && p() && e('0');       // 步骤4：测试空字符串参数
r($pivotTest->isShowLastRowTest('invalid')) && p() && e('0'); // 步骤5：测试无效参数