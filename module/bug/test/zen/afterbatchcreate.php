#!/usr/bin/env php
<?php

/**

title=测试 bugZen::afterBatchCreate();
timeout=0
cid=15421

- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug1, $output1  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug2, $output2  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug3, $output3  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug4, $output4  @1
- 执行$result5 && $afterCount > $beforeCount @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug6, $output6  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug7, $output7  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备execution测试数据
$execution = zenData('project');
$execution->id->range('101-103');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->gen(3);

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 测试1:无execution的bug,不处理看板数据
$bug1 = new stdclass();
$bug1->id = 1;
$bug1->execution = 0;
$output1 = array();
r($bugTest->afterBatchCreateTest($bug1, $output1)) && p() && e('1');

// 测试2:有execution但无laneID和columnID的bug,更新看板泳道
$bug2 = new stdclass();
$bug2->id = 2;
$bug2->execution = 101;
$output2 = array();
r($bugTest->afterBatchCreateTest($bug2, $output2)) && p() && e('1');

// 测试3:有execution和output中的laneID、columnID的bug,添加看板单元格
$bug3 = new stdclass();
$bug3->id = 3;
$bug3->execution = 101;
$output3 = array('laneID' => 1, 'columnID' => 1);
r($bugTest->afterBatchCreateTest($bug3, $output3)) && p() && e('1');

// 测试4:有execution和bug中的laneID、columnID的bug,添加看板单元格
$bug4 = new stdclass();
$bug4->id = 4;
$bug4->execution = 101;
$bug4->laneID = 2;
$output4 = array('laneID' => 1, 'columnID' => 2);
r($bugTest->afterBatchCreateTest($bug4, $output4)) && p() && e('1');

// 测试5:有uploadImage和imageFile的bug,插入文件记录
$file = zenData('file');
$file->id->range('1-10');
$file->objectType->range('bug');
$file->objectID->range('1-10');
$file->gen(0);
$bug5 = new stdclass();
$bug5->id = 5;
$bug5->execution = 0;
$bug5->uploadImage = 1;
$bug5->imageFile = array('pathname' => 'test.png', 'title' => 'test image', 'extension' => 'png', 'size' => 1024);
$output5 = array();
$beforeCount = $tester->dao->select('count(*) as count')->from(TABLE_FILE)->fetch('count');
$result5 = $bugTest->afterBatchCreateTest($bug5, $output5);
$afterCount = $tester->dao->select('count(*) as count')->from(TABLE_FILE)->fetch('count');
r($result5 && $afterCount > $beforeCount) && p() && e('1');

// 测试6:只有uploadImage但无imageFile的bug,不插入文件
$bug6 = new stdclass();
$bug6->id = 6;
$bug6->execution = 0;
$bug6->uploadImage = 1;
$output6 = array();
r($bugTest->afterBatchCreateTest($bug6, $output6)) && p() && e('1');

// 测试7:有imageFile但无uploadImage标志的bug,不插入文件
$bug7 = new stdclass();
$bug7->id = 7;
$bug7->execution = 0;
$bug7->imageFile = array('pathname' => 'test2.png', 'title' => 'test image 2', 'extension' => 'png', 'size' => 2048);
$output7 = array();
r($bugTest->afterBatchCreateTest($bug7, $output7)) && p() && e('1');