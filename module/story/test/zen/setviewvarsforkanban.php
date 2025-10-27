#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setViewVarsForKanban();
timeout=0
cid=0

- 步骤1：正常kanban执行属性executionType @kanban
- 步骤2：空objectID属性executionType @~~
- 步骤3：非kanban类型属性executionType @~~
- 步骤4：指定regionID属性regionDefault @2
- 步骤5：不同storyType属性executionType @kanban

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,看板项目,普通项目,测试项目');
$project->type->range('project,project,kanban,project,project');
$project->status->range('doing{5}');
$project->deleted->range('0{5}');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->setViewVarsForKanbanTest(3, array(), 'story')) && p('executionType') && e('kanban'); // 步骤1：正常kanban执行
r($storyTest->setViewVarsForKanbanTest(0, array(), 'story')) && p('executionType') && e('~~'); // 步骤2：空objectID
r($storyTest->setViewVarsForKanbanTest(1, array(), 'story')) && p('executionType') && e('~~'); // 步骤3：非kanban类型
r($storyTest->setViewVarsForKanbanTest(3, array('regionID' => 2, 'laneID' => 4), 'story')) && p('regionDefault') && e('2'); // 步骤4：指定regionID
r($storyTest->setViewVarsForKanbanTest(3, array(), 'requirement')) && p('executionType') && e('kanban'); // 步骤5：不同storyType