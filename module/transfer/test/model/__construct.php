#!/usr/bin/env php
<?php

/**

title=测试 transferModel::__construct();
timeout=0
cid=19308

- 步骤1：正常初始化模型属性transferConfig @1
- 步骤2：设置cookie为100属性maxImport @100
- 步骤3：设置cookie为0属性maxImport @0
- 步骤4：不设置cookie属性maxImport @0
- 步骤5：验证transferConfig配置对象属性hasTransferConfig @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($transferTest->constructTest()) && p('transferConfig') && e('1'); // 步骤1：正常初始化模型
r($transferTest->constructTest(100)) && p('maxImport') && e('100'); // 步骤2：设置cookie为100
r($transferTest->constructTest(0)) && p('maxImport') && e('0'); // 步骤3：设置cookie为0
r($transferTest->constructTest('')) && p('maxImport') && e('0'); // 步骤4：不设置cookie
r($transferTest->constructTest()) && p('hasTransferConfig') && e('1'); // 步骤5：验证transferConfig配置对象