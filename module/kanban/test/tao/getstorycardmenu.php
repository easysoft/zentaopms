#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getStoryCardMenu();
timeout=0
cid=0

- 步骤1：测试空story数组 @0
- 步骤2：测试单个active状态的story @1
- 步骤3：测试draft状态的story @1
- 步骤4：测试reviewing状态的story @1
- 步骤5：测试closed状态的story @1
- 步骤6：测试execution无产品关联 @1
- 步骤7：测试多个story @2
- 步骤8：测试不同stage的story @1
- 步骤9：测试execution有产品关联 @2
- 步骤10：测试多种状态混合 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('story')->loadYaml('getstorycardmenu/story', false, 2)->gen(10);
zenData('project')->loadYaml('getstorycardmenu/project', false, 2)->gen(3);
zenData('user')->loadYaml('getstorycardmenu/user', false, 2)->gen(5);

su('admin');

$kanbanTest = new kanbanTaoTest();

global $tester;
$execution1 = $tester->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq(1)->fetch();
$execution2 = $tester->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq(2)->fetch();
$execution3 = $tester->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq(3)->fetch();

$story3 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(3)->fetch();
$story1 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(1)->fetch();
$story2 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch();
$story5 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(5)->fetch();
$story6 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(6)->fetch();

$stories1 = array(3 => $story3);
$stories2 = array(1 => $story1);
$stories3 = array(2 => $story2);
$stories4 = array(5 => $story5);
$stories5 = array(6 => $story6);
$stories6 = array();
$stories7 = array(3 => $story3, 6 => $story6);

r(count($kanbanTest->getStoryCardMenuTest($execution1, $stories6))) && p() && e('0'); // 步骤1：测试空story数组
r(count($kanbanTest->getStoryCardMenuTest($execution1, $stories1))) && p() && e('1'); // 步骤2：测试单个active状态的story
r(count($kanbanTest->getStoryCardMenuTest($execution1, $stories2))) && p() && e('1'); // 步骤3：测试draft状态的story
r(count($kanbanTest->getStoryCardMenuTest($execution1, $stories3))) && p() && e('1'); // 步骤4：测试reviewing状态的story
r(count($kanbanTest->getStoryCardMenuTest($execution1, $stories4))) && p() && e('1'); // 步骤5：测试closed状态的story
r(count($kanbanTest->getStoryCardMenuTest($execution3, $stories5))) && p() && e('1'); // 步骤6：测试execution无产品关联
r(count($kanbanTest->getStoryCardMenuTest($execution2, $stories7))) && p() && e('2'); // 步骤7：测试多个story
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(6 => $story6)))) && p() && e('1'); // 步骤8：测试不同stage的story
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(2 => $story2, 3 => $story3)))) && p() && e('2'); // 步骤9：测试execution有产品关联
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(1 => $story1, 5 => $story5, 6 => $story6)))) && p() && e('3'); // 步骤10：测试多种状态混合