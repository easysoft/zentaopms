#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::__construct();
timeout=0
cid=0

- 步骤1：正常初始化pivot模型实例属性objectType @pivotModel
- 步骤2：验证父类model初始化成功属性parentInit @1
- 步骤3：验证loadBIDAO方法调用成功属性biDAOLoaded @1
- 步骤4：验证bi模型加载成功属性biModelLoaded @1
- 步骤5：验证实例化后对象属性完整属性instanceComplete @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 对于构造函数测试，不需要特殊数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->__constructTest()) && p('objectType') && e('pivotModel'); // 步骤1：正常初始化pivot模型实例
r($pivotTest->__constructTest()) && p('parentInit') && e('1'); // 步骤2：验证父类model初始化成功
r($pivotTest->__constructTest()) && p('biDAOLoaded') && e('1'); // 步骤3：验证loadBIDAO方法调用成功
r($pivotTest->__constructTest()) && p('biModelLoaded') && e('1'); // 步骤4：验证bi模型加载成功
r($pivotTest->__constructTest()) && p('instanceComplete') && e('1'); // 步骤5：验证实例化后对象属性完整