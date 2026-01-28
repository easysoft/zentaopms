#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::addChildColumnCell();
timeout=0
cid=16968

- 步骤1：正常情况，i=0属性success @1
- 步骤2：正常情况，i=1属性success @1
- 步骤3：父列不存在属性success @0
- 步骤4：子列ID为0仍可创建属性success @1
- 步骤5：父列ID超大值属性success @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$kanbancellTable = zenData('kanbancell');
$kanbancellTable->kanban->range('1-5');
$kanbancellTable->lane->range('1-3');
$kanbancellTable->column->range('1-10');
$kanbancellTable->type->range('common{5}');
$kanbancellTable->cards->range('1,2,3{2},4{2}');
$kanbancellTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->addChildColumnCellTest(1, 11, 0)) && p('success') && e('1'); // 步骤1：正常情况，i=0
r($kanbanTest->addChildColumnCellTest(2, 12, 1)) && p('success') && e('1'); // 步骤2：正常情况，i=1
r($kanbanTest->addChildColumnCellTest(999, 13, 0)) && p('success') && e('0'); // 步骤3：父列不存在
r($kanbanTest->addChildColumnCellTest(3, 0, 0)) && p('success') && e('1'); // 步骤4：子列ID为0仍可创建
r($kanbanTest->addChildColumnCellTest(999999, 14, 0)) && p('success') && e('0'); // 步骤5：父列ID超大值