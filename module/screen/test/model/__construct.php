#!/usr/bin/env php
<?php

/**

title=测试 screenModel::__construct();
timeout=0
cid=0

- 步骤1：验证基础对象初始化
 - 属性objectType @1
 - 属性parentInit @1
 - 属性filterExists @1
- 步骤2：验证BI相关组件加载
 - 属性biDAOLoaded @1
 - 属性biModelLoaded @1
- 步骤3：验证filter基础属性初始化
 - 属性filterScreen @1
 - 属性filterYear @1
 - 属性filterMonth @1
- 步骤4：验证filter扩展属性初始化
 - 属性filterDept @1
 - 属性filterAccount @1
 - 属性filterCharts @1
- 步骤5：验证构造函数完整性
 - 属性filterExists @1
 - 属性biModelLoaded @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($screenTest->__constructTest()) && p('objectType,parentInit,filterExists') && e('1,1,1'); // 步骤1：验证基础对象初始化
r($screenTest->__constructTest()) && p('biDAOLoaded,biModelLoaded') && e('1,1'); // 步骤2：验证BI相关组件加载
r($screenTest->__constructTest()) && p('filterScreen,filterYear,filterMonth') && e('1,1,1'); // 步骤3：验证filter基础属性初始化
r($screenTest->__constructTest()) && p('filterDept,filterAccount,filterCharts') && e('1,1,1'); // 步骤4：验证filter扩展属性初始化
r($screenTest->__constructTest()) && p('filterExists,biModelLoaded') && e('1,1'); // 步骤5：验证构造函数完整性