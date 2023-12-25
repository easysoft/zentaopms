#!/usr/bin/env php
<?php

/**

title=productModel->buildRoadmapForUI();
cid=0

- 空数据 @0
- 分支没有数据 @0
- 执行$data[$year][0] @1
- 执行$data[$year][1] @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$product = $tester->loadModel('product');

$emptyRoadmaps     = array();
$year              = '2023';
$roadmaps          = array();
$roadmaps['total'] = 6;
$roadmaps[$year][0][0][5] = new stdclass();
$roadmaps[$year][0][0][5]->id      = 1;
$roadmaps[$year][0][0][5]->product = 1;
$roadmaps[$year][0][0][5]->name    = 'release1';
$roadmaps[$year][0][0][5]->marker  = true;
$roadmaps[$year][0][0][5]->date    = '2023-08-14';
$roadmaps[$year][0][0][5]->status  = 'normal';
$roadmaps[$year][0][0][4] = new stdclass();
$roadmaps[$year][0][0][4]->id      = 2;
$roadmaps[$year][0][0][4]->product = 1;
$roadmaps[$year][0][0][4]->name    = 'release2';
$roadmaps[$year][0][0][4]->marker  = false;
$roadmaps[$year][0][0][4]->date    = '2023-08-15';
$roadmaps[$year][0][0][4]->status  = 'normal';
$roadmaps[$year][0][0][3] = new stdclass();
$roadmaps[$year][0][0][3]->id      = 3;
$roadmaps[$year][0][0][3]->product = 1;
$roadmaps[$year][0][0][3]->name    = 'release3';
$roadmaps[$year][0][0][3]->marker  = false;
$roadmaps[$year][0][0][3]->date    = '2023-08-16';
$roadmaps[$year][0][0][3]->status  = 'normal';
$roadmaps[$year][0][0][2] = new stdclass();
$roadmaps[$year][0][0][2]->id      = 4;
$roadmaps[$year][0][0][2]->product = 1;
$roadmaps[$year][0][0][2]->name    = 'release4';
$roadmaps[$year][0][0][2]->marker  = false;
$roadmaps[$year][0][0][2]->date    = '2023-08-17';
$roadmaps[$year][0][0][2]->status  = 'normal';
$roadmaps[$year][0][0][1] = new stdclass();
$roadmaps[$year][0][0][1]->id      = 1;
$roadmaps[$year][0][0][1]->product = 1;
$roadmaps[$year][0][0][1]->title   = 'plan1';
$roadmaps[$year][0][0][1]->begin   = '2023-08-28';
$roadmaps[$year][0][0][1]->end     = '2023-09-01';
$roadmaps[$year][0][0][1]->status  = 'wait';
$roadmaps[$year][0][0][0] = new stdclass();
$roadmaps[$year][0][0][0]->id      = 2;
$roadmaps[$year][0][0][0]->product = 1;
$roadmaps[$year][0][0][0]->title   = 'plan2';
$roadmaps[$year][0][0][0]->begin   = '2023-09-04';
$roadmaps[$year][0][0][0]->end     = '2023-09-08';
$roadmaps[$year][0][0][0]->status  = 'wait';

r($product->buildRoadmapForUI($emptyRoadmaps)) && p() && e('0'); //空数据
r($product->buildRoadmapForUI($roadmaps, 1)) && p() && e('0'); //分支没有数据

$data = $product->buildRoadmapForUI($roadmaps, 0);
r(count($data[$year][0])) && p() && e('1');
r(count($data[$year][1])) && p() && e('5');
