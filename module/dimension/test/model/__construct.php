#!/usr/bin/env php
<?php

/**

title=测试 dimensionModel::__construct();
timeout=0
cid=0

- 步骤1：正常构造函数调用属性result @normal
- 步骤2：模型实例存在属性result @1
- 步骤3：实例类型验证属性result @1
- 步骤4：类名验证属性result @dimensionModel
- 步骤5：父类构造函数调用属性result @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dimension.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$dimensionTest = new dimensionTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($dimensionTest->__constructTest('normal')) && p('result') && e('normal'); // 步骤1：正常构造函数调用
r($dimensionTest->__constructTest('modelExists')) && p('result') && e('1'); // 步骤2：模型实例存在
r($dimensionTest->__constructTest('modelInstance')) && p('result') && e('1'); // 步骤3：实例类型验证
r($dimensionTest->__constructTest('className')) && p('result') && e('dimensionModel'); // 步骤4：类名验证
r($dimensionTest->__constructTest('parentConstructor')) && p('result') && e('1'); // 步骤5：父类构造函数调用