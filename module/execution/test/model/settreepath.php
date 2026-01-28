#!/usr/bin/env php
<?php

/**

title=测试 executionModel::setTreePath();
timeout=0
cid=16366

- 步骤1：父项目为project类型的stage设置TreePath
 - 第2条的path属性 @
 - 第2条的grade属性 @1
- 步骤2：父项目为stage类型的stage设置TreePath
 - 第3条的path属性 @
 - 第3条的grade属性 @1
- 步骤3：多层级嵌套stage设置TreePath
 - 第4条的path属性 @
 - 第4条的grade属性 @1
- 步骤4：不存在的execution设置TreePath @alse
- 步骤5：父项目不存在的execution设置TreePath @alse
- 步骤6：无效的executionID设置TreePath @alse
- 步骤7：已存在path的execution重新设置TreePath
 - 第8条的path属性 @
 - 第8条的grade属性 @7

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

r($executionTest->setTreePathTest(2)) && p('2:path,grade') && e(',1,2,,1');                    // 步骤1：父项目为project类型的stage设置TreePath
r($executionTest->setTreePathTest(3)) && p('3:path,grade') && e(',1,2,3,,2');                  // 步骤2：父项目为stage类型的stage设置TreePath
r($executionTest->setTreePathTest(4)) && p('4:path,grade') && e(',1,2,3,4,,3');                // 步骤3：多层级嵌套stage设置TreePath
r($executionTest->setTreePathTest(999)) && p() && e(false);                                     // 步骤4：不存在的execution设置TreePath
r($executionTest->setTreePathTest(6)) && p() && e(false);                                       // 步骤5：父项目不存在的execution设置TreePath
r($executionTest->setTreePathTest(0)) && p() && e(false);                                       // 步骤6：无效的executionID设置TreePath
r($executionTest->setTreePathTest(8)) && p('8:path,grade') && e(',7,8,,1');                    // 步骤7：已存在path的execution重新设置TreePath