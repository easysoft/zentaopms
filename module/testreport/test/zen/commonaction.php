#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::commonAction();
timeout=0
cid=0

- 步骤1:objectType为product且objectID为1的正常情况 @1
- 步骤2:objectType为execution且objectID为2的正常情况 @2
- 步骤3:objectType为project且objectID为3的正常情况 @3
- 步骤4:objectType为product且objectID为0时返回默认产品ID @1
- 步骤5:objectType为execution且objectID为0时返回默认执行ID @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
// commonAction 方法主要进行权限检查和菜单设置,使用模拟数据

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$testreportTest = new testreportTest();

// 5. 🔴 强制要求:必须包含至少5个测试步骤
r($testreportTest->commonActionTest(1, 'product')) && p() && e('1'); // 步骤1:objectType为product且objectID为1的正常情况
r($testreportTest->commonActionTest(2, 'execution')) && p() && e('2'); // 步骤2:objectType为execution且objectID为2的正常情况
r($testreportTest->commonActionTest(3, 'project')) && p() && e('3'); // 步骤3:objectType为project且objectID为3的正常情况
r($testreportTest->commonActionTest(0, 'product')) && p() && e('1'); // 步骤4:objectType为product且objectID为0时返回默认产品ID
r($testreportTest->commonActionTest(0, 'execution')) && p() && e('0'); // 步骤5:objectType为execution且objectID为0时返回默认执行ID