#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildOutlineTree();
timeout=0
cid=16185

- 步骤1:空数组返回空数组 @0
- 步骤2:单层结构返回顶级节点
 - 第0条的id属性 @0
 - 第1条的id属性 @1
- 步骤3:两层结构返回父节点ID第0条的id属性 @0
- 步骤4:多层嵌套正确递归构建第0条的id属性 @0
- 步骤5:混合结构处理多个顶级节点
 - 第0条的id属性 @0
 - 第1条的id属性 @1
- 步骤6:从指定父节点构建子树
 - 第0条的id属性 @1
 - 第1条的id属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

// 测试步骤1: 测试空数组输入
$emptyList = array();
r($docTest->buildOutlineTreeTest($emptyList)) && p() && e('0'); // 步骤1:空数组返回空数组

// 测试步骤2: 测试单层树结构(只有顶级节点)
$singleLevel = array(
    0 => array('id' => 0, 'title' => array('html' => 'Title 1'), 'parent' => -1, 'level' => 1),
    1 => array('id' => 1, 'title' => array('html' => 'Title 2'), 'parent' => -1, 'level' => 1)
);
r($docTest->buildOutlineTreeTest($singleLevel)) && p('0:id;1:id') && e('0;1'); // 步骤2:单层结构返回顶级节点

// 测试步骤3: 测试两层树结构
$twoLevel = array(
    0 => array('id' => 0, 'title' => array('html' => 'Parent'), 'parent' => -1, 'level' => 1),
    1 => array('id' => 1, 'title' => array('html' => 'Child'), 'parent' => 0, 'level' => 2)
);
r($docTest->buildOutlineTreeTest($twoLevel)) && p('0:id') && e('0'); // 步骤3:两层结构返回父节点ID

// 测试步骤4: 测试多层嵌套树结构
$multiLevel = array(
    0 => array('id' => 0, 'title' => array('html' => 'L1'), 'parent' => -1, 'level' => 1),
    1 => array('id' => 1, 'title' => array('html' => 'L2'), 'parent' => 0, 'level' => 2),
    2 => array('id' => 2, 'title' => array('html' => 'L3'), 'parent' => 1, 'level' => 3)
);
r($docTest->buildOutlineTreeTest($multiLevel)) && p('0:id') && e('0'); // 步骤4:多层嵌套正确递归构建

// 测试步骤5: 测试混合层级结构
$mixedLevel = array(
    0 => array('id' => 0, 'title' => array('html' => 'P1'), 'parent' => -1, 'level' => 1),
    1 => array('id' => 1, 'title' => array('html' => 'P2'), 'parent' => -1, 'level' => 1),
    2 => array('id' => 2, 'title' => array('html' => 'C1'), 'parent' => 0, 'level' => 2),
    3 => array('id' => 3, 'title' => array('html' => 'C2'), 'parent' => 0, 'level' => 2)
);
r($docTest->buildOutlineTreeTest($mixedLevel)) && p('0:id;1:id') && e('0;1'); // 步骤5:混合结构处理多个顶级节点

// 测试步骤6: 测试从指定parentID开始构建子树
$subTree = array(
    0 => array('id' => 0, 'title' => array('html' => 'Root'), 'parent' => -1, 'level' => 1),
    1 => array('id' => 1, 'title' => array('html' => 'Child1'), 'parent' => 0, 'level' => 2),
    2 => array('id' => 2, 'title' => array('html' => 'Child2'), 'parent' => 0, 'level' => 2)
);
r($docTest->buildOutlineTreeTest($subTree, 0)) && p('0:id;1:id') && e('1;2'); // 步骤6:从指定父节点构建子树