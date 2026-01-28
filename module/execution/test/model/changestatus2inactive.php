#!/usr/bin/env php
<?php

/**

title=测试 executionModel::changeStatus2Inactive();
timeout=0
cid=16279

- 步骤1：修改顶级父阶段状态为suspended，子阶段未全部挂起/关闭 @'顶级阶段A',

- 步骤2：修改叶子阶段状态为suspended，正常挂起流程 @empty
- 步骤3：修改叶子阶段状态为closed，正常关闭流程 @empty
- 步骤4：修改已关闭状态的阶段为suspended @empty
- 步骤5：修改状态为closed，所有子阶段未全部关闭 @'顶级阶段B',

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 准备用户数据
zenData('user')->gen(5);
su('admin');

// 3. 准备执行阶段数据，包含更多测试场景
$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('瀑布项目1,顶级阶段A,子阶段A1,叶子阶段A11,叶子阶段A12,顶级阶段B,子阶段B1,叶子阶段B11,独立阶段C,混合状态阶段D');
$execution->type->range('project,stage{9}');
$execution->project->range('0,1{9}');
$execution->parent->range('0,1,2,2,2,1,6,7,1,1');
$execution->path->range("`,1,`,`,1,2,`,`,1,2,3,`,`,1,2,4,`,`,1,2,5,`,`,1,6,`,`,1,6,7,`,`,1,6,7,8,`,`,1,9,`,`,1,10,`");
$execution->status->range('doing,doing,doing,wait,doing,suspended,suspended,closed,wait,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220312 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

// 4. 创建测试实例
$executionTest = new executionModelTest();

// 5. 执行测试步骤（至少5个）
r($executionTest->changeStatus2InactiveObject(2, 'suspended')) && p('') && e("'顶级阶段A',"); // 步骤1：修改顶级父阶段状态为suspended，子阶段未全部挂起/关闭
r($executionTest->changeStatus2InactiveObject(4, 'suspended')) && p('') && e('empty');        // 步骤2：修改叶子阶段状态为suspended，正常挂起流程
r($executionTest->changeStatus2InactiveObject(5, 'closed')) && p('') && e('empty');           // 步骤3：修改叶子阶段状态为closed，正常关闭流程
r($executionTest->changeStatus2InactiveObject(8, 'suspended')) && p('') && e('empty');        // 步骤4：修改已关闭状态的阶段为suspended
r($executionTest->changeStatus2InactiveObject(6, 'closed')) && p('') && e("'顶级阶段B',");    // 步骤5：修改状态为closed，所有子阶段未全部关闭