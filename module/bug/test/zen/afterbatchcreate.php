#!/usr/bin/env php
<?php

/**

title=测试 bugZen::afterBatchCreate();
timeout=0
cid=0

- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug1, array  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug2, array  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug3, array  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug4, array  @1
- 执行bugTest模块的afterBatchCreateTest方法，参数是$bug5, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

su('admin');

$bugTest = new bugTest();

$bug1 = new stdclass();
$bug1->id = 1;
$bug1->execution = 101;
$bug1->laneID = 1;

$bug2 = new stdclass();
$bug2->id = 2;
$bug2->execution = 102;

$bug3 = new stdclass();
$bug3->id = 3;
$bug3->execution = 101;
$bug3->uploadImage = 'image.jpg';
$bug3->imageFile = array('title' => 'test.jpg', 'size' => 1024);

$bug4 = new stdclass();
$bug4->id = 4;

$bug5 = new stdclass();
$bug5->id = 5;
$bug5->execution = 101;
$bug5->laneID = '';

r($bugTest->afterBatchCreateTest($bug1, array('laneID' => 1, 'columnID' => 2))) && p() && e(1);
r($bugTest->afterBatchCreateTest($bug2, array('laneID' => 0, 'columnID' => 0))) && p() && e(1);
r($bugTest->afterBatchCreateTest($bug3, array())) && p() && e(1);
r($bugTest->afterBatchCreateTest($bug4, array())) && p() && e(1);
r($bugTest->afterBatchCreateTest($bug5, array('laneID' => 0, 'columnID' => 0))) && p() && e(1);