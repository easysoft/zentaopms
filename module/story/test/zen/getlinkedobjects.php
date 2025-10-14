#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getLinkedObjects();
timeout=0
cid=0

- 步骤1：正常故事对象测试
 - 属性bugs @2
 - 属性cases @2
 - 属性modulePath @1
 - 属性storyModule @1
- 步骤2：有fromBug和linkStoryTitles的故事对象测试
 - 属性fromBug @1
 - 属性storyProducts @1
- 步骤3：有twins的故事对象测试属性twins @2
- 步骤4：不存在的故事对象测试
 - 属性bugs @0
 - 属性cases @0
- 步骤5：无模块的故事对象测试属性storyModule @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('软件需求1,软件需求2,软件需求3,软件需求4,软件需求5,软件需求6,软件需求7,软件需求8,软件需求9,软件需求10');
$story->product->range('1-3:3R');
$story->module->range('0,1801,1802,1803,0,1801,1802,1803,0,1801');
$story->fromBug->range('0,2,0,0,0,0,0,0,0,0');
$story->twins->range(',,3,,,,,,,');
$story->gen(10);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->title->range('缺陷1,缺陷2,缺陷3,缺陷4,缺陷5,缺陷6,缺陷7,缺陷8,缺陷9,缺陷10');
$bug->story->range('1,1,0,2,2,0,0,0,0,0');
$bug->status->range('active{5},resolved{3},closed{2}');
$bug->pri->range('1-4:R');
$bug->severity->range('1-4:R');
$bug->deleted->range('0');
$bug->gen(10);

$case = zenData('case');
$case->id->range('1-10');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5,测试用例6,测试用例7,测试用例8,测试用例9,测试用例10');
$case->story->range('1,1,2,2,0,0,0,0,0,0');
$case->status->range('normal{7},blocked{2},investigate{1}');
$case->pri->range('1-4:R');
$case->deleted->range('0');
$case->gen(10);

$module = zenData('module');
$module->id->range('1801-1810');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$module->path->range(',1801,,1802,,1803,,1804,,1805,');
$module->parent->range('0,1801,0,1802,0,1803,0,1804,0,1805');
$module->deleted->range('0');
$module->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getLinkedObjectsTest((object)array('id' => 1, 'fromBug' => 0, 'twins' => '', 'module' => 1801))) && p('bugs,cases,modulePath,storyModule') && e('2,2,1,1'); // 步骤1：正常故事对象测试
r($storyTest->getLinkedObjectsTest((object)array('id' => 2, 'fromBug' => 2, 'twins' => '', 'module' => 1802, 'linkStoryTitles' => array('3' => '软件需求3')))) && p('fromBug,storyProducts') && e('1,1'); // 步骤2：有fromBug和linkStoryTitles的故事对象测试
r($storyTest->getLinkedObjectsTest((object)array('id' => 3, 'fromBug' => 0, 'twins' => '4,5', 'module' => 1803))) && p('twins') && e('2'); // 步骤3：有twins的故事对象测试
r($storyTest->getLinkedObjectsTest((object)array('id' => 999, 'fromBug' => 0, 'twins' => '', 'module' => 1801))) && p('bugs,cases') && e('0,0'); // 步骤4：不存在的故事对象测试
r($storyTest->getLinkedObjectsTest((object)array('id' => 5, 'fromBug' => 0, 'twins' => '', 'module' => 0))) && p('storyModule') && e('0'); // 步骤5：无模块的故事对象测试