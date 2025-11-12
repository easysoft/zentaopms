#!/usr/bin/env php
<?php

/**

title=测试 docZen::getOutlineParentID();
timeout=0
cid=0

- 测试1:空大纲列表 @0
- 测试2:单层级结构 @1
- 测试3:多层级结构找level为2的父级 @2
- 测试4:多层级结构找level为1的父级 @1
- 测试5:没有更小层级的元素 @0
- 测试6:复杂层级结构 @5
- 测试7:多个同级元素找最近父级 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->getOutlineParentIDTest(array(), 2)) && p() && e('0'); // 测试1:空大纲列表
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 1), 2 => array('level' => 2)), 2)) && p() && e('1'); // 测试2:单层级结构
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 1), 2 => array('level' => 2), 3 => array('level' => 3)), 3)) && p() && e('2'); // 测试3:多层级结构找level为2的父级
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 1), 2 => array('level' => 2), 3 => array('level' => 3)), 2)) && p() && e('1'); // 测试4:多层级结构找level为1的父级
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 3), 2 => array('level' => 3), 3 => array('level' => 3)), 2)) && p() && e('0'); // 测试5:没有更小层级的元素
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 1), 2 => array('level' => 2), 3 => array('level' => 3), 4 => array('level' => 3), 5 => array('level' => 2)), 4)) && p() && e('5'); // 测试6:复杂层级结构
r($docTest->getOutlineParentIDTest(array(1 => array('level' => 1), 2 => array('level' => 2), 3 => array('level' => 2), 4 => array('level' => 2), 5 => array('level' => 1), 6 => array('level' => 2)), 2)) && p() && e('5'); // 测试7:多个同级元素找最近父级