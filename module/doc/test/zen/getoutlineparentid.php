#!/usr/bin/env php
<?php

/**

title=测试 docZen::getOutlineParentID();
timeout=0
cid=0

- 步骤1：空大纲列表 @0
- 步骤2：单级大纲 @0
- 步骤3：多级递减 - 寻找级别2的父级，应该是级别1的项目1 @1
- 步骤4：复杂多级 - 寻找级别3的父级，从末尾开始找，应该是级别2的项目4 @4
- 步骤5：当前级别过小 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备
$table = zenData('doclib');
$table->id->range('1-5');
$table->type->range('mine,custom,product,project');
$table->name->range('我的文档库,团队文档库,产品文档库,项目文档库');
$table->gen(4);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docTest();

// 5. 测试步骤
r($docTest->getOutlineParentIDTest(array(), 1)) && p() && e('0');                                                              // 步骤1：空大纲列表
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 2), 2 => array('level' => 3)), 2)) && p() && e('0');         // 步骤2：单级大纲
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 1), 2 => array('level' => 2), 3 => array('level' => 3)), 2)) && p() && e('1'); // 步骤3：多级递减 - 寻找级别2的父级，应该是级别1的项目1
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 1), 2 => array('level' => 2), 3 => array('level' => 3), 4 => array('level' => 2)), 3)) && p() && e('4'); // 步骤4：复杂多级 - 寻找级别3的父级，从末尾开始找，应该是级别2的项目4
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 2), 2 => array('level' => 3), 3 => array('level' => 4)), 1)) && p() && e('0'); // 步骤5：当前级别过小