#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::updateRegion();
timeout=0
cid=16966

- 步骤1：正常更新看板区域名称属性name @更新后的区域名称
- 步骤2：更新区域名称为空值第name条的0属性 @『区域名称』不能为空。
- 步骤3：更新区域名称为空格字符串第name条的0属性 @『区域名称』不能为空。
- 步骤4：更新区域名称为超长字符串第name条的0属性 @『区域名称』长度应当不超过『255』，且大于『0』。
- 步骤5：更新不存在的区域ID @0
- 步骤6：验证更新后数据库记录的编辑者字段属性lastEditedBy @admin
- 步骤7：验证边界值长度的区域名称更新属性name @AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

$kanbanregion = zenData('kanbanregion');
$kanbanregion->id->range('1-5');
$kanbanregion->space->range('1');
$kanbanregion->kanban->range('1');
$kanbanregion->name->range('区域1,区域2,区域3,区域4,区域5');
$kanbanregion->order->range('1-5');
$kanbanregion->createdBy->range('admin');
$kanbanregion->createdDate->range('`2023-01-01 00:00:00`');
$kanbanregion->lastEditedBy->range('admin');
$kanbanregion->lastEditedDate->range('`2023-01-01 00:00:00`');
$kanbanregion->deleted->range('0');
$kanbanregion->gen(5);

su('admin');

$kanban = new kanbanTest();

r($kanban->updateRegionTest(1, '更新后的区域名称')) && p('name') && e('更新后的区域名称'); // 步骤1：正常更新看板区域名称
r($kanban->updateRegionTest(2, '')) && p('name:0') && e('『区域名称』不能为空。'); // 步骤2：更新区域名称为空值
r($kanban->updateRegionTest(3, '  ')) && p('name:0') && e('『区域名称』不能为空。'); // 步骤3：更新区域名称为空格字符串
r($kanban->updateRegionTest(4, str_repeat('超长字符串测试', 50))) && p('name:0') && e('『区域名称』长度应当不超过『255』，且大于『0』。'); // 步骤4：更新区域名称为超长字符串
r($kanban->updateRegionTest(999, '不存在的区域')) && p() && e('0'); // 步骤5：更新不存在的区域ID
r($kanban->updateRegionTest(5, '边界长度测试')) && p('lastEditedBy') && e('admin'); // 步骤6：验证更新后数据库记录的编辑者字段
r($kanban->updateRegionTest(1, str_repeat('A', 255))) && p('name') && e('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'); // 步骤7：验证边界值长度的区域名称更新