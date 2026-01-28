#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::updateExecutionCell();
timeout=0
cid=16996

- 步骤1：正常更新单元格卡片属性updated @1
- 步骤2：更新不存在单元格属性result @success
- 步骤3：清空单元格卡片属性updated @1
- 步骤4：更新多个卡片ID属性updated @1
- 步骤5：字符串参数转换属性updated @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('kanbancell');
$table->kanban->range('1,2,3,4,5');
$table->lane->range('1,1,2,2,1');
$table->column->range('1,2,1,2,3');
$table->type->range('common,common,common,common,common');
$table->cards->range(',1,2,,3,4,5,,6,7,,8,,');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($kanbanTest->updateExecutionCellTest(1, 1, 1, ',10,11,12,')) && p('updated') && e('1'); // 步骤1：正常更新单元格卡片
r($kanbanTest->updateExecutionCellTest(999, 999, 999, ',1,2,')) && p('result') && e('success'); // 步骤2：更新不存在单元格
r($kanbanTest->updateExecutionCellTest(1, 1, 1, '')) && p('updated') && e('1'); // 步骤3：清空单元格卡片
r($kanbanTest->updateExecutionCellTest(2, 2, 1, ',20,21,22,23,')) && p('updated') && e('1'); // 步骤4：更新多个卡片ID
r($kanbanTest->updateExecutionCellTest(3, 1, 2, ',30,31,')) && p('updated') && e('1'); // 步骤5：字符串参数转换