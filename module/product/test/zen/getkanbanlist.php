#!/usr/bin/env php
<?php

/**

title=测试 productZen::getKanbanList();
timeout=0
cid=17587

- 步骤1:测试browseType为my时返回数组类型 @1
- 步骤2:测试browseType为other时返回数组类型 @1
- 步骤3:测试browseType为空字符串时返回数组类型 @1
- 步骤4:测试返回数组的第一个元素包含key字段第0条的key属性 @1
- 步骤5:测试browseType为other时返回数组元素包含key字段第0条的key属性 @0
- 步骤6:测试browseType为my时返回数组有元素 @1
- 步骤7:测试browseType为other时返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('productplan')->loadYaml('productplan', false, 2)->gen(5);
zenData('project')->loadYaml('project', false, 2)->gen(10);
zenData('release')->loadYaml('release', false, 2)->gen(5);
zenData('projectproduct')->loadYaml('projectproduct', false, 2)->gen(10);

su('admin');

$productTest = new productZenTest();

r(is_array($productTest->getKanbanListTest('my'))) && p() && e('1'); // 步骤1:测试browseType为my时返回数组类型
r(is_array($productTest->getKanbanListTest('other'))) && p() && e('1'); // 步骤2:测试browseType为other时返回数组类型
r(is_array($productTest->getKanbanListTest(''))) && p() && e('1'); // 步骤3:测试browseType为空字符串时返回数组类型
r($productTest->getKanbanListTest('my')) && p('0:key') && e('1'); // 步骤4:测试返回数组的第一个元素包含key字段
r($productTest->getKanbanListTest('other')) && p('0:key') && e('0'); // 步骤5:测试browseType为other时返回数组元素包含key字段
r(count($productTest->getKanbanListTest('my'))) && p() && e('1'); // 步骤6:测试browseType为my时返回数组有元素
r(count($productTest->getKanbanListTest('other'))) && p() && e('0'); // 步骤7:测试browseType为other时返回空数组