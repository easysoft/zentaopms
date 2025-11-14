#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::__construct();
timeout=0
cid=19820

- 步骤1：正常构造对象验证父类构造方法调用属性parentCalled @1
- 步骤2：验证语言设置被正确配置属性langSet @1
- 步骤3：验证继承关系正确性属性inheritance @1
- 步骤4：验证多次调用构造方法的稳定性
 - 属性parentCalled @1
 - 属性langSet @1
- 步骤5：验证对象模型实例化正确性属性objectModel @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($zanodeTest->constructTest()) && p('parentCalled') && e('1'); // 步骤1：正常构造对象验证父类构造方法调用
r($zanodeTest->constructTest()) && p('langSet') && e('1'); // 步骤2：验证语言设置被正确配置
r($zanodeTest->constructTest()) && p('inheritance') && e('1'); // 步骤3：验证继承关系正确性
r($zanodeTest->constructTest()) && p('parentCalled,langSet') && e('1,1'); // 步骤4：验证多次调用构造方法的稳定性
r($zanodeTest->constructTest()) && p('objectModel') && e('1'); // 步骤5：验证对象模型实例化正确性