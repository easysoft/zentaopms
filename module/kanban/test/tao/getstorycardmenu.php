#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getStoryCardMenu();
timeout=0
cid=0

- 步骤1:空story数组输入 @0
- 步骤2:单个active状态story @1
- 步骤3:draft状态story不能创建任务 @1
- 步骤4:reviewing状态story不能创建任务 @1
- 步骤5:closed状态story不能创建任务 @1
- 步骤6:execution无产品关联不显示unlink @1
- 步骤7:多个story返回对应数量菜单 @3
- 步骤8:测试不同stage的story菜单 @1

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

$story1 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(1)->fetch();
$story2 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch();
$story3 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(3)->fetch();
$story4 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(4)->fetch();
$story5 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(5)->fetch();
$story6 = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(6)->fetch();

r(count($kanbanTest->getStoryCardMenuTest($execution1, array()))) && p() && e('0'); // 步骤1:空story数组输入
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(3 => $story3)))) && p() && e('1'); // 步骤2:单个active状态story
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(1 => $story1)))) && p() && e('1'); // 步骤3:draft状态story不能创建任务
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(2 => $story2)))) && p() && e('1'); // 步骤4:reviewing状态story不能创建任务
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(5 => $story5)))) && p() && e('1'); // 步骤5:closed状态story不能创建任务
r(count($kanbanTest->getStoryCardMenuTest($execution3, array(6 => $story6)))) && p() && e('1'); // 步骤6:execution无产品关联不显示unlink
r(count($kanbanTest->getStoryCardMenuTest($execution1, array(1 => $story1, 3 => $story3, 6 => $story6)))) && p() && e('3'); // 步骤7:多个story返回对应数量菜单
r(count($kanbanTest->getStoryCardMenuTest($execution2, array(4 => $story4)))) && p() && e('1'); // 步骤8:测试不同stage的story菜单