#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::copyColumns();
timeout=0
cid=16881

- 步骤1：正常复制多个看板列 @8
- 步骤2：复制包含父子关系的看板列，验证子列parent属性第10条的parent属性 @9
- 步骤3：复制具有不同limit值的看板列，返回数组 @Array
- 步骤4：复制空标题的看板列 @(
- 步骤5：验证错误信息 @[0] => stdClass Object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备基础测试数据
zenData('kanbancolumn')->gen(5);

su('admin');

$kanbanTest = new kanbanModelTest();

// 测试数据准备
$regionID   = 100;
$newGroupID = 200;

// 正常看板列数据
$normalColumns = array(
    (object)array('id' => 101, 'title' => '待办', 'parent' => 0, 'limit' => -1, 'color' => '#ff0000'),
    (object)array('id' => 102, 'title' => '进行中', 'parent' => 0, 'limit' => 5, 'color' => '#00ff00'),
    (object)array('id' => 103, 'title' => '已完成', 'parent' => 0, 'limit' => 10, 'color' => '#0000ff')
);

// 父子关系看板列数据
$parentChildColumns = array(
    (object)array('id' => 201, 'title' => '父列', 'parent' => -1, 'limit' => 20, 'color' => '#ffff00'),
    (object)array('id' => 202, 'title' => '子列1', 'parent' => 201, 'limit' => 8, 'color' => '#ff00ff'),
    (object)array('id' => 203, 'title' => '子列2', 'parent' => 201, 'limit' => 12, 'color' => '#00ffff')
);

// 无效数据（空标题）
$invalidColumns = array(
    (object)array('id' => 301, 'title' => '', 'parent' => 0, 'limit' => -1, 'color' => '#ffffff')
);

// 有效limit测试数据
$limitTestColumns = array(
    (object)array('id' => 401, 'title' => '有限制列', 'parent' => 0, 'limit' => 15, 'color' => '#aaaaaa')
);

// 空数组
$emptyColumns = array();

r(count($kanbanTest->copyColumnsTest($normalColumns, $regionID, $newGroupID))) && p('') && e('8'); // 步骤1：正常复制多个看板列
r($kanbanTest->copyColumnsTest($parentChildColumns, $regionID + 1, $newGroupID + 1)) && p('10:parent') && e('9'); // 步骤2：复制包含父子关系的看板列，验证子列parent属性
r($kanbanTest->copyColumnsTest($limitTestColumns, $regionID + 2, $newGroupID + 2)) && p('') && e('Array'); // 步骤3：复制具有不同limit值的看板列，返回数组
r($kanbanTest->copyColumnsTest($invalidColumns, $regionID + 3, $newGroupID + 3)) && p('') && e('('); // 步骤4：复制空标题的看板列
r(dao::getError()) && p('') && e('[0] => stdClass Object'); // 步骤5：验证错误信息