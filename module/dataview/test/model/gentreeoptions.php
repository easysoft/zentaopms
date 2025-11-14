#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::genTreeOptions();
timeout=0
cid=15952

- 步骤1：单层路径创建 @Root Node
- 步骤2：多层路径创建 @Level 3
- 步骤3：现有节点扩展 @New Node
- 步骤4：多个兄弟节点第一个 @Branch 1
- 步骤5：多个兄弟节点第二个 @Branch 2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dataview.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$dataviewTest = new dataviewTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
$tree1 = new stdclass();
r($dataviewTest->genTreeOptionsTest($tree1, array('root' => 'Root Node'), array('root'))->children[0]->title) && p() && e('Root Node'); // 步骤1：单层路径创建
$tree2 = new stdclass();
r($dataviewTest->genTreeOptionsTest($tree2, array('level1' => 'Level 1', 'level2' => 'Level 2', 'level3' => 'Level 3'), array('level1', 'level2', 'level3'))->children[0]->children[0]->children[0]->title) && p() && e('Level 3'); // 步骤2：多层路径创建
r($dataviewTest->genTreeOptionsTestWithExisting(array('existing' => 'Existing Node', 'new' => 'New Node'), array('existing', 'new'))->children[0]->children[0]->title) && p() && e('New Node'); // 步骤3：现有节点扩展
r($dataviewTest->genTreeOptionsTestMultiple()['tree']->children[0]->title) && p() && e('Branch 1'); // 步骤4：多个兄弟节点第一个
r($dataviewTest->genTreeOptionsTestMultiple()['tree']->children[1]->title) && p() && e('Branch 2'); // 步骤5：多个兄弟节点第二个