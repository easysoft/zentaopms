#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::__construct();
timeout=0
cid=0

- 步骤1：正常实例化验证类名 @aiappModel
- 步骤2：验证继承基类 @1
- 步骤3：验证加载ai模型 @aiModel
- 步骤4：验证dao对象 @mysql
- 步骤5：验证config属性存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/aiapp.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiappTest = new aiappTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(get_class($aiappTest->__constructTest())) && p() && e('aiappModel'); // 步骤1：正常实例化验证类名
r($aiappTest->__constructTest() instanceof model) && p() && e('1'); // 步骤2：验证继承基类
r(get_class($aiappTest->__constructTest()->ai)) && p() && e('aiModel'); // 步骤3：验证加载ai模型
r(get_class($aiappTest->__constructTest()->dao)) && p() && e('mysql'); // 步骤4：验证dao对象
r(isset($aiappTest->__constructTest()->config)) && p() && e('1'); // 步骤5：验证config属性存在