#!/usr/bin/env php
<?php

/**

title=测试 projectZen::expandExecutionIdList();
timeout=0
cid=0

- 测试步骤1:传入空数组 >> 期望返回空数组
- 测试步骤2:传入单个执行对象无子节点 >> 期望返回该执行ID
- 测试步骤3:传入单个执行对象有一层子节点 >> 期望返回父节点和子节点的ID列表
- 测试步骤4:传入多层嵌套子节点的执行对象 >> 期望返回所有层级的执行ID
- 测试步骤5:传入多个执行对象混合场景 >> 期望返回所有执行ID包括嵌套的子节点
- 测试步骤6:传入有两层子节点的复杂结构 >> 期望正确递归展开所有ID
- 测试步骤7:传入多个执行其中部分有子节点 >> 期望返回所有相关执行ID

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$projectTest = new projectZenTest();

// 测试步骤1:空数组输入
r($projectTest->expandExecutionIdListTest(array())) && p() && e('0');

// 测试步骤2:单个执行对象,无子节点
$execution1 = new stdclass();
$execution1->id = 1;
$execution1->children = array();
r($projectTest->expandExecutionIdListTest(array($execution1))) && p('1') && e('1');

// 测试步骤3:单个执行对象,有一层子节点
$execution2 = new stdclass();
$execution2->id = 2;
$child1 = new stdclass();
$child1->id = 3;
$child1->children = array();
$execution2->children = array($child1);
r($projectTest->expandExecutionIdListTest(array($execution2))) && p('2,3') && e('2,3');

// 测试步骤4:多层嵌套子节点
$execution3 = new stdclass();
$execution3->id = 10;
$child2 = new stdclass();
$child2->id = 11;
$grandchild1 = new stdclass();
$grandchild1->id = 12;
$grandchild1->children = array();
$child2->children = array($grandchild1);
$execution3->children = array($child2);
r($projectTest->expandExecutionIdListTest(array($execution3))) && p('10,11,12') && e('10,11,12');

// 测试步骤5:多个执行对象混合场景
$execution4 = new stdclass();
$execution4->id = 20;
$execution4->children = array();
$execution5 = new stdclass();
$execution5->id = 21;
$child3 = new stdclass();
$child3->id = 22;
$child3->children = array();
$execution5->children = array($child3);
r($projectTest->expandExecutionIdListTest(array($execution4, $execution5))) && p('20,21,22') && e('20,21,22');

// 测试步骤6:复杂多分支结构
$execution6 = new stdclass();
$execution6->id = 30;
$child4 = new stdclass();
$child4->id = 31;
$child5 = new stdclass();
$child5->id = 32;
$grandchild2 = new stdclass();
$grandchild2->id = 33;
$grandchild2->children = array();
$child5->children = array($grandchild2);
$child4->children = array();
$execution6->children = array($child4, $child5);
r($projectTest->expandExecutionIdListTest(array($execution6))) && p('30,31,32,33') && e('30,31,32,33');

// 测试步骤7:三层嵌套的深层结构
$execution7 = new stdclass();
$execution7->id = 40;
$child6 = new stdclass();
$child6->id = 41;
$grandchild3 = new stdclass();
$grandchild3->id = 42;
$greatgrandchild1 = new stdclass();
$greatgrandchild1->id = 43;
$greatgrandchild1->children = array();
$grandchild3->children = array($greatgrandchild1);
$child6->children = array($grandchild3);
$execution7->children = array($child6);
r($projectTest->expandExecutionIdListTest(array($execution7))) && p('40,41,42,43') && e('40,41,42,43');
