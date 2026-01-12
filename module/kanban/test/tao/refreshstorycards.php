#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshStoryCards();
timeout=0
cid=0

- 步骤1:测试需求卡片刷新,验证wait和projected阶段需求在backlog列属性backlog @,2,
- 步骤2:测试需求卡片刷新,验证designing阶段需求在designing列属性designing @~~
- 步骤3:测试需求卡片刷新,验证designed阶段需求在designed列属性designed @~~
- 步骤4:测试需求卡片刷新,验证developing阶段需求在developing列属性developing @~~
- 步骤5:测试需求卡片刷新,验证developed阶段需求在developed列属性developed @~~
- 步骤6:测试需求卡片刷新,验证testing阶段需求在testing列属性testing @~~
- 步骤7:测试需求卡片刷新,验证tested阶段需求在tested列属性tested @~~
- 步骤8:测试需求卡片刷新,验证verified阶段需求在verified列属性verified @~~

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备(根据需要配置)
zendata('product')->gen(1);
zendata('projectproduct')->gen(1);
zendata('story')->loadYaml('refreshstorycards/story', false, 2)->gen(10);
zendata('project')->loadYaml('refreshstorycards/execution', false, 2)->gen(1);
$projectstory = zendata('projectstory');
$projectstory->project->range('1');
$projectstory->product->range('1');
$projectstory->story->range('1-10');
$projectstory->version->range('1');
$projectstory->order->range('1-10');
$projectstory->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$kanbanTest = new kanbanTaoTest();

// 5. 强制要求:必须包含至少5个测试步骤
$cardPairs = array('backlog' => '', 'ready' => '', 'design' => '', 'designing' => '', 'designed' => '', 'develop' => '', 'developing' => '', 'developed' => '', 'test' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'rejected' => '', 'pending' => '', 'released' => '', 'closed' => '');
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('backlog', '|') && e(',2,'); // 步骤1:测试需求卡片刷新,验证wait和projected阶段需求在backlog列
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('designing') && e('~~'); // 步骤2:测试需求卡片刷新,验证designing阶段需求在designing列
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('designed') && e('~~'); // 步骤3:测试需求卡片刷新,验证designed阶段需求在designed列
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('developing') && e('~~'); // 步骤4:测试需求卡片刷新,验证developing阶段需求在developing列
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('developed') && e('~~'); // 步骤5:测试需求卡片刷新,验证developed阶段需求在developed列
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('testing') && e('~~'); // 步骤6:测试需求卡片刷新,验证testing阶段需求在testing列
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('tested') && e('~~'); // 步骤7:测试需求卡片刷新,验证tested阶段需求在tested列
r($kanbanTest->refreshStoryCardsTest($cardPairs, 1, '')) && p('verified') && e('~~'); // 步骤8:测试需求卡片刷新,验证verified阶段需求在verified列