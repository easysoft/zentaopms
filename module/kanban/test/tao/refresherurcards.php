#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshERURCards();
timeout=0
cid=0

- 步骤1:测试story类型的卡片刷新,验证wait阶段
 - 属性wait @
- 步骤2:测试story类型的卡片刷新,验证planned阶段
 - 属性planned @
- 步骤3:测试story类型的卡片刷新,验证projected阶段
 - 属性projected @
- 步骤4:测试story类型的卡片刷新,验证developing阶段
 - 属性developing @
- 步骤5:测试包含已有卡片的刷新,验证卡片移动
 - 属性wait @

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备(根据需要配置)
zendata('product')->gen(1);
zendata('projectproduct')->gen(1);
zendata('story')->loadYaml('refresherurcards/story', false, 2)->gen(15);
zendata('project')->loadYaml('refresherurcards/project', false, 2)->gen(1);
$projectstory = zendata('projectstory');
$projectstory->project->range('1');
$projectstory->product->range('1');
$projectstory->story->range('1-15');
$projectstory->version->range('1');
$projectstory->order->range('1-15');
$projectstory->gen(15);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$kanbanTest = new kanbanTaoTest();

// 5. 强制要求:必须包含至少5个测试步骤
$cardPairs = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
r($kanbanTest->refreshERURCardsTest($cardPairs, 1, '', 'story')) && p('wait') && e(',2,'); // 步骤1:测试story类型的卡片刷新,验证wait阶段
r($kanbanTest->refreshERURCardsTest($cardPairs, 1, '', 'story')) && p('planned') && e(',4,'); // 步骤2:测试story类型的卡片刷新,验证planned阶段
r($kanbanTest->refreshERURCardsTest($cardPairs, 1, '', 'story')) && p('projected') && e(',6,'); // 步骤3:测试story类型的卡片刷新,验证projected阶段
r($kanbanTest->refreshERURCardsTest($cardPairs, 1, '', 'story')) && p('developing') && e(',8,'); // 步骤4:测试story类型的卡片刷新,验证developing阶段
r($kanbanTest->refreshERURCardsTest(array('wait' => ',100,', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => ''), 1, '', 'story')) && p('wait') && e(',2,100,'); // 步骤5:测试包含已有卡片的刷新,验证卡片移动