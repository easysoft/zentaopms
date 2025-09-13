#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildOutlineTree();
timeout=0
cid=0

- 执行$result1[0]['title']['html'] @一级标题1
- 执行$result2 @0
- 执行$result3 @3
- 执行$childCount @1
- 执行$itemCount @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：正常树形结构构建 - 测试基本的父子关系构建
$outlineList1 = array(
    0 => array('id' => 0, 'title' => array('html' => '一级标题1'), 'level' => 1, 'parent' => -1),
    1 => array('id' => 1, 'title' => array('html' => '二级标题1'), 'level' => 2, 'parent' => 0),
    2 => array('id' => 2, 'title' => array('html' => '二级标题2'), 'level' => 2, 'parent' => 0),
    3 => array('id' => 3, 'title' => array('html' => '一级标题2'), 'level' => 1, 'parent' => -1)
);
$result1 = $docTest->buildOutlineTreeTest($outlineList1);
r($result1[0]['title']['html']) && p() && e('一级标题1');

// 步骤2：空数组输入测试 - 应该返回空数组
$emptyList = array();
$result2 = $docTest->buildOutlineTreeTest($emptyList);
r(count($result2)) && p() && e('0');

// 步骤3：单层级结构测试 - 测试没有子项的情况
$singleLevelList = array(
    0 => array('id' => 0, 'title' => array('html' => '标题A'), 'level' => 1, 'parent' => -1),
    1 => array('id' => 1, 'title' => array('html' => '标题B'), 'level' => 1, 'parent' => -1),
    2 => array('id' => 2, 'title' => array('html' => '标题C'), 'level' => 1, 'parent' => -1)
);
$result3 = $docTest->buildOutlineTreeTest($singleLevelList);
r(count($result3)) && p() && e('3');

// 步骤4：多层级嵌套结构测试 - 测试深层次嵌套
$nestedList = array(
    0 => array('id' => 0, 'title' => array('html' => '一级'), 'level' => 1, 'parent' => -1),
    1 => array('id' => 1, 'title' => array('html' => '二级'), 'level' => 2, 'parent' => 0),
    2 => array('id' => 2, 'title' => array('html' => '三级'), 'level' => 3, 'parent' => 1)
);
$result4 = $docTest->buildOutlineTreeTest($nestedList);
$childCount = isset($result4[0]['items'][0]['items']) ? count($result4[0]['items'][0]['items']) : 0;
r($childCount) && p() && e('1');

// 步骤5：复杂层级关系测试 - 测试多个分支的复杂结构
$complexList = array(
    0 => array('id' => 0, 'title' => array('html' => 'Root1'), 'level' => 1, 'parent' => -1),
    1 => array('id' => 1, 'title' => array('html' => 'Child1-1'), 'level' => 2, 'parent' => 0),
    2 => array('id' => 2, 'title' => array('html' => 'Child1-2'), 'level' => 2, 'parent' => 0),
    3 => array('id' => 3, 'title' => array('html' => 'Root2'), 'level' => 1, 'parent' => -1),
    4 => array('id' => 4, 'title' => array('html' => 'Child2-1'), 'level' => 2, 'parent' => 3)
);
$result5 = $docTest->buildOutlineTreeTest($complexList);
$itemCount = isset($result5[0]['items']) ? count($result5[0]['items']) : 0;
r($itemCount) && p() && e('2');