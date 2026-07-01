#!/usr/bin/env php
<?php

/**

title=测试 executionModel::setTreePath();
timeout=0
cid=16366

- 步骤1：父项目为project类型的stage设置TreePath第2条的path属性 @,1,2,
- 步骤2：父项目为project类型的stage设置TreePath第2条的grade属性 @1
- 步骤3：父项目为stage类型的stage设置TreePath第3条的path属性 @,1,2,3,
- 步骤4：父项目为stage类型的stage设置TreePath第3条的grade属性 @2
- 步骤5：多层级嵌套stage设置TreePath第4条的path属性 @,1,2,3,4,
- 步骤6：多层级嵌套stage设置TreePath第4条的grade属性 @3
- 步骤7：不存在的execution设置TreePath @0
- 步骤8：父项目不存在的execution设置TreePath第6条的path属性 @3
- 步骤9：无效的executionID设置TreePath @0
- 步骤10：已存在path的execution重新设置TreePath第8条的path属性 @,7,8,
- 步骤11：已存在path的execution重新设置TreePath第8条的grade属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('根项目,第一层stage,第二层stage,第三层stage,独立项目,孤儿stage,测试项目,测试stage1,测试stage2,测试stage3');
$execution->type->range('project,stage,stage,stage,project,stage,project,stage,stage,stage');
$execution->parent->range('0,1,2,3,0,999,0,7,8,9');
$execution->path->range(',1,,1,2,,1,2,3,,5,,7,,7,8,,7,8,9,');
$execution->grade->range('0,1,2,3,0,1,0,1,2,3');
$execution->status->range('wait');
$execution->gen(10);

su('admin');

$executionTest = new executionModelTest();

r($executionTest->setTreePathTest(2))   && p('2:path', '|')  && e(',1,2,');   // 步骤1：父项目为project类型的stage设置TreePath
r($executionTest->setTreePathTest(2))   && p('2:grade')      && e('1');       // 步骤2：父项目为project类型的stage设置TreePath
r($executionTest->setTreePathTest(3))   && p('3:path', '|')  && e(',1,2,3,'); // 步骤3：父项目为stage类型的stage设置TreePath
r($executionTest->setTreePathTest(3))   && p('3:grade')      && e('2');       // 步骤4：父项目为stage类型的stage设置TreePath
r($executionTest->setTreePathTest(4))   && p('4:path', '|')  && e(',1,2,3,4,'); // 步骤5：多层级嵌套stage设置TreePath
r($executionTest->setTreePathTest(4))   && p('4:grade')      && e('3');       // 步骤6：多层级嵌套stage设置TreePath
r($executionTest->setTreePathTest(999)) && p()               && e('0');       // 步骤7：不存在的execution设置TreePath
r($executionTest->setTreePathTest(6))   && p('6:path')       && e('3');       // 步骤8：父项目不存在的execution设置TreePath
r($executionTest->setTreePathTest(0))   && p()               && e('0');       // 步骤9：无效的executionID设置TreePath
r($executionTest->setTreePathTest(8))   && p('8:path', '|')  && e(',7,8,');   // 步骤10：已存在path的execution重新设置TreePath
r($executionTest->setTreePathTest(8))   && p('8:grade')      && e('1');       // 步骤11：已存在path的execution重新设置TreePath