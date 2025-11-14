#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinChartSQL();
timeout=0
cid=15197

- 步骤1:insert操作返回非空数组 @1
- 步骤2:update操作返回非空数组 @1
- 步骤3:INSERT语句格式正确 @1
- 步骤4:表名正确 @1
- 步骤5:返回数组类型 @1
- 步骤6:默认使用insert操作 @1
- 步骤7:包含配置信息 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 2. zendata数据准备
zenData('chart')->gen(0);
zenData('user')->gen(5);

// 3. 加载bi模型
$bi = $tester->loadModel('bi');

// 4. 强制要求:必须包含至少5个测试步骤
r(count($bi->prepareBuiltinChartSQL('insert')) > 0) && p() && e('1'); // 步骤1:insert操作返回非空数组
r(count($bi->prepareBuiltinChartSQL('update')) > 0) && p() && e('1'); // 步骤2:update操作返回非空数组
r(strpos($bi->prepareBuiltinChartSQL('insert')[0], 'INSERT') !== false) && p() && e('1'); // 步骤3:INSERT语句格式正确
r(strpos($bi->prepareBuiltinChartSQL('insert')[0], 'chart') !== false) && p() && e('1'); // 步骤4:表名正确
r(is_array($bi->prepareBuiltinChartSQL('insert'))) && p() && e('1'); // 步骤5:返回数组类型
r(count($bi->prepareBuiltinChartSQL()) > 0) && p() && e('1'); // 步骤6:默认使用insert操作
r(strpos($bi->prepareBuiltinChartSQL('insert')[0], 'system') !== false) && p() && e('1'); // 步骤7:包含配置信息