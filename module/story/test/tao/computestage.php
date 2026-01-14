#!/usr/bin/env php
<?php

/**

title=测试 storyTao::computeStage();
timeout=0
cid=18614

- 步骤1：全部子需求为wait状态 @wait
- 步骤2：包含inroadmap状态 @inroadmap
- 步骤3：包含incharter状态 @incharter
- 步骤4：包含planned状态 @planned
- 步骤5：包含projected状态 @projected
- 步骤6：包含开发中状态 @developing
- 步骤7：包含交付中状态 @delivering
- 步骤8：全部子需求已交付 @delivered

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTaoTest();

// 4. 测试步骤1：全部子需求为wait状态
$children1 = array();
$child1 = new stdclass();
$child1->stage = 'wait';
$child1->closedReason = '';
$children1[] = $child1;
$child2 = new stdclass();
$child2->stage = 'wait';
$child2->closedReason = '';
$children1[] = $child2;

r($storyTest->computeStageTest($children1)) && p() && e('wait'); // 步骤1：全部子需求为wait状态

// 5. 测试步骤2：包含inroadmap状态的子需求
$children2 = array();
$child1 = new stdclass();
$child1->stage = 'wait';
$child1->closedReason = '';
$children2[] = $child1;
$child2 = new stdclass();
$child2->stage = 'inroadmap';
$child2->closedReason = '';
$children2[] = $child2;

r($storyTest->computeStageTest($children2)) && p() && e('inroadmap'); // 步骤2：包含inroadmap状态

// 6. 测试步骤3：包含incharter状态的子需求
$children3 = array();
$child1 = new stdclass();
$child1->stage = 'wait';
$child1->closedReason = '';
$children3[] = $child1;
$child2 = new stdclass();
$child2->stage = 'incharter';
$child2->closedReason = '';
$children3[] = $child2;

r($storyTest->computeStageTest($children3)) && p() && e('incharter'); // 步骤3：包含incharter状态

// 7. 测试步骤4：包含planned状态的子需求
$children4 = array();
$child1 = new stdclass();
$child1->stage = 'wait';
$child1->closedReason = '';
$children4[] = $child1;
$child2 = new stdclass();
$child2->stage = 'planned';
$child2->closedReason = '';
$children4[] = $child2;

r($storyTest->computeStageTest($children4)) && p() && e('planned'); // 步骤4：包含planned状态

// 8. 测试步骤5：包含projected状态的子需求
$children5 = array();
$child1 = new stdclass();
$child1->stage = 'wait';
$child1->closedReason = '';
$children5[] = $child1;
$child2 = new stdclass();
$child2->stage = 'projected';
$child2->closedReason = '';
$children5[] = $child2;

r($storyTest->computeStageTest($children5)) && p() && e('projected'); // 步骤5：包含projected状态

// 9. 测试步骤6：包含开发中状态的子需求
$children6 = array();
$child1 = new stdclass();
$child1->stage = 'developing';
$child1->closedReason = '';
$children6[] = $child1;
$child2 = new stdclass();
$child2->stage = 'testing';
$child2->closedReason = '';
$children6[] = $child2;

r($storyTest->computeStageTest($children6)) && p() && e('developing'); // 步骤6：包含开发中状态

// 10. 测试步骤7：包含交付中状态的子需求
$children7 = array();
$child1 = new stdclass();
$child1->stage = 'delivering';
$child1->closedReason = '';
$children7[] = $child1;
$child2 = new stdclass();
$child2->stage = 'released';
$child2->closedReason = '';
$children7[] = $child2;

r($storyTest->computeStageTest($children7)) && p() && e('delivering'); // 步骤7：包含交付中状态

// 11. 测试步骤8：全部子需求已交付
$children8 = array();
$child1 = new stdclass();
$child1->stage = 'delivered';
$child1->closedReason = '';
$children8[] = $child1;
$child2 = new stdclass();
$child2->stage = 'closed';
$child2->closedReason = 'done';
$children8[] = $child2;

r($storyTest->computeStageTest($children8)) && p() && e('delivered'); // 步骤8：全部子需求已交付