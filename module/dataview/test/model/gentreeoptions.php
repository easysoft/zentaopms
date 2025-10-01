#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::genTreeOptions();
timeout=0
cid=0

- 步骤1：验证根节点title @Root Node
- 步骤2：验证深层节点title @Level 3
- 步骤3：验证新子节点title @New Node
- 步骤4：验证第一个节点title @Branch 1
- 步骤5：验证第二个节点title @Branch 2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dataview.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$dataviewTest = new dataviewTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试1：单层路径创建
$tree1 = new stdclass();
$result1 = $dataviewTest->genTreeOptionsTest($tree1, array('root' => 'Root Node'), array('root'));
r($result1->children[0]->title) && p() && e('Root Node'); // 步骤1：验证根节点title

// 测试2：多层路径创建
$tree2 = new stdclass();
$result2 = $dataviewTest->genTreeOptionsTest($tree2, array('level1' => 'Level 1', 'level2' => 'Level 2', 'level3' => 'Level 3'), array('level1', 'level2', 'level3'));
r($result2->children[0]->children[0]->children[0]->title) && p() && e('Level 3'); // 步骤2：验证深层节点title

// 测试3：现有节点扩展
$tree3 = new stdclass();
$tree3->children = array();
$existingChild = new stdclass();
$existingChild->title = 'Existing Node';
$existingChild->value = 'existing';
$tree3->children[] = $existingChild;
$result3 = $dataviewTest->genTreeOptionsTest($tree3, array('existing' => 'Existing Node', 'new' => 'New Node'), array('existing', 'new'));
r($result3->children[0]->children[0]->title) && p() && e('New Node'); // 步骤3：验证新子节点title

// 测试4和5：多个兄弟节点
$tree4 = new stdclass();
$dataviewTest->genTreeOptionsTest($tree4, array('branch1' => 'Branch 1'), array('branch1'));
$result4 = $dataviewTest->genTreeOptionsTest($tree4, array('branch2' => 'Branch 2'), array('branch2'));
r($result4->children[0]->title) && p() && e('Branch 1'); // 步骤4：验证第一个节点title
r($result4->children[1]->title) && p() && e('Branch 2'); // 步骤5：验证第二个节点title