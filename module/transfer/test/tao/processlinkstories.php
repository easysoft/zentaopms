#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';

$productplan = zdTable('productplan');
$productplan->title->range('计划1,计划2');
$productplan->createdDate->range('`' . date('Y-m-d') . '`');
$productplan->gen(2);

zdTable('story')->gen(20);
$relation = zdTable('relation');
$relation->AID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18,9,19');
$relation->BID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8,19,9');
$relation->gen(18);

$stories[12]          = new stdclass();
$stories[12]->id      = 12;
$stories[12]->parent  = 0;
$stories[12]->plan    = '1';
$stories[12]->type    = 'story';
$stories[12]->product = 1;
$stories[13]          = new stdclass();
$stories[13]->id      = 13;
$stories[13]->parent  = -1;
$stories[13]->plan    = '0';
$stories[13]->type    = 'requirement';
$stories[13]->product = 4;
$stories[14]          = new stdclass();
$stories[14]->id      = 14;
$stories[14]->parent  = 13;
$stories[14]->plan    = '0';
$stories[14]->type    = 'story';
$stories[14]->product = 4;
$stories[15]          = new stdclass();
$stories[15]->id      = 15;
$stories[15]->parent  = 11;
$stories[15]->plan    = '0';
$stories[15]->product = 4;
$stories[15]->type    = 'requirement';

su('admin');

/**

title=测试 transfer->getCascadeList();
timeout=0
cid=1

*/
global $tester;
$transfer = $tester->loadModel('transfer');

r($transfer->processLinkStories($stories)) && p('12:planTitle') && e('计划1'); //查看planTitle字段。
r($transfer->processLinkStories($stories)) && p('14:parentName') && e('用户需求13'); //查看parentName字段
