#!/usr/bin/env php
<?php

/**

title=测试 storyModel::createTwins();
timeout=0
cid=18488

- 步骤1：正常多分支孪生需求创建 @5
- 步骤2：空分支数组情况 @7
- 步骤3：单一分支创建 @8
- 步骤4：多分支复杂数据创建 @9
- 步骤5：包含bugID和todoID的孪生创建 @12

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(3);
zenData('project')->gen(3);
zenData('bug')->gen(3);
zenData('todo')->gen(3);
zenData('relation')->gen(0);
zenData('storyreview')->gen(0);
zenData('projectstory')->gen(0);

$story = zenData('story');
$story->type->range('requirement,story{10}');
$story->parent->range('0,0,0,0');
$story->product->range('1,2,3');
$story->version->range('1');
$story->gen(4);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-10');
$storySpec->gen(4);

su('admin');

global $tester;
$tester->loadModel('story');

// 测试数据1：正常多分支孪生需求
$data1 = new stdclass();
$data1->product     = 1;
$data1->module      = 0;
$data1->branches    = array(0, 1);
$data1->modules     = array(1, 2);
$data1->plans       = array('0', '0');
$data1->plan        = '1';
$data1->assignedTo  = '';
$data1->source      = '';
$data1->sourceNote  = '';
$data1->feedbackBy  = '';
$data1->notifyEmail = '';
$data1->parent      = 0;
$data1->title       = 'Twin Story Test';
$data1->color       = '';
$data1->category    = 'feature';
$data1->pri         = 3;
$data1->estimate    = 1;
$data1->spec        = 'Twin story specification';
$data1->verify      = 'Twin story verification';
$data1->keywords    = '';
$data1->type        = 'story';
$data1->status      = 'active';
$data1->version     = 1;
$data1->openedBy    = 'admin';
$data1->openedDate  = date('Y-m-d H:i:s');
$data1->mailto      = '';
$data1->reviewer[]  = 'admin';

// 测试数据2：空分支数组
$data2 = clone $data1;
$data2->branches = array();
$data2->title = 'Empty Branches Story';

// 测试数据3：单一分支
$data3 = clone $data1;
$data3->branches = array(0);
$data3->modules = array(1);
$data3->plans = array('0');
$data3->title = 'Single Branch Story';

// 测试数据4：多分支复杂数据
$data4 = clone $data1;
$data4->branches = array(0, 1, 2);
$data4->modules = array(1, 2, 3);
$data4->plans = array('1', '2', '0');
$data4->title = 'Multi Branch Complex Story';

// 测试数据5：包含bugID和todoID
$data5 = clone $data1;
$data5->title = 'Story With Bug and Todo';

r($tester->story->createTwins($data1, 0, 0)) && p() && e('5'); // 步骤1：正常多分支孪生需求创建
r($tester->story->createTwins($data2, 0, 0)) && p() && e('7'); // 步骤2：空分支数组情况
r($tester->story->createTwins($data3, 0, 0)) && p() && e('8'); // 步骤3：单一分支创建
r($tester->story->createTwins($data4, 0, 0)) && p() && e('9'); // 步骤4：多分支复杂数据创建
r($tester->story->createTwins($data5, 1, 1, 'extra', 1)) && p() && e('12'); // 步骤5：包含bugID和todoID的孪生创建