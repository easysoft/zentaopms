#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::__construct();
timeout=0
cid=19780

- 步骤1：验证父类构造函数调用属性parentCalled @1
- 步骤2：验证语言项设置属性langSet @1
- 步骤3：验证继承关系属性inheritance @1
- 步骤4：验证对象模型创建属性objectModel @1
- 步骤5：验证多项属性一致性
 - 属性parentCalled @1
 - 属性langSet @1
 - 属性inheritance @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($zanodeTest->constructTest()) && p('parentCalled') && e('1'); // 步骤1：验证父类构造函数调用
r($zanodeTest->constructTest()) && p('langSet') && e('1'); // 步骤2：验证语言项设置
r($zanodeTest->constructTest()) && p('inheritance') && e('1'); // 步骤3：验证继承关系
r($zanodeTest->constructTest()) && p('objectModel') && e('1'); // 步骤4：验证对象模型创建
r($zanodeTest->constructTest()) && p('parentCalled,langSet,inheritance') && e('1,1,1'); // 步骤5：验证多项属性一致性