#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewExecutionStory();
timeout=0
cid=0

- 步骤1：preview模式有效执行ID和有效条件属性hasData @1
- 步骤2：preview模式无效执行ID属性hasData @0
- 步骤3：preview模式空设置参数属性hasData @0
- 步骤4：list模式有效ID列表属性hasData @1
- 步骤5：list模式空ID列表属性hasData @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->product->range('1-2');
$storyTable->title->range('执行需求1,执行需求2,执行需求3,执行需求4,执行需求5');
$storyTable->status->range('active,draft,closed');
$storyTable->type->range('story');
$storyTable->stage->range('planned,developing,testing,verified,released');
$storyTable->pri->range('1-4');
$storyTable->estimate->range('3-8');
$storyTable->assignedTo->range('admin,user1,user2');
$storyTable->gen(10);

$executionTable = zenData('project');
$executionTable->id->range('11-15');
$executionTable->name->range('执行1,执行2,执行3,执行4,执行5');
$executionTable->type->range('sprint');
$executionTable->status->range('wait,doing,suspended,closed');
$executionTable->project->range('1-2');
$executionTable->gen(5);

$projectStoryTable = zenData('projectstory');
$projectStoryTable->project->range('11-15');
$projectStoryTable->story->range('1-10');
$projectStoryTable->version->range('1');
$projectStoryTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->previewExecutionStoryTest('setting', array('action' => 'preview', 'execution' => 11, 'condition' => 'all'), '')) && p('hasData') && e('1'); // 步骤1：preview模式有效执行ID和有效条件

r($docTest->previewExecutionStoryTest('setting', array('action' => 'preview', 'execution' => 999, 'condition' => 'all'), '')) && p('hasData') && e('0'); // 步骤2：preview模式无效执行ID

r($docTest->previewExecutionStoryTest('setting', array(), '')) && p('hasData') && e('0'); // 步骤3：preview模式空设置参数

r($docTest->previewExecutionStoryTest('list', array(), '1,2,3')) && p('hasData') && e('1'); // 步骤4：list模式有效ID列表

r($docTest->previewExecutionStoryTest('list', array(), '')) && p('hasData') && e('0'); // 步骤5：list模式空ID列表