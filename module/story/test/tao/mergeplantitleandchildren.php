#!/usr/bin/env php
<?php
/**

title=测试 storyModel->mergePlanTitleAndChildren();
timeout=0
cid=18654

- 不传入任何数据。 @0
- 只传入产品。 @0
- 传入产品和需求，查看planTitle字段。 @计划1
- 传入产品和需求，查看子需求是否存在。 @0
- 传入产品和需求，查看originParent字段。 @11

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

$productplan = zenData('productplan');
$productplan->title->range('计划1,计划2');
$productplan->createdDate->range('`' . date('Y-m-d') . '`');
$productplan->gen(2);

zenData('story')->gen(20);

$relation = zenData('relation');
$relation->AID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8,19,9');
$relation->BID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18,9,19');
$relation->gen(18);

global $tester;
$storyModel = $tester->loadModel('story');

$stories[12]                = new stdclass();
$stories[12]->id            = 12;
$stories[12]->parent        = 0;
$stories[12]->plan          = '1';
$stories[12]->demand        = '0';
$stories[12]->type          = 'story';
$stories[12]->path          = ',12,';
$stories[12]->parentVersion = '0';
$stories[13]                = new stdclass();
$stories[13]->id            = 13;
$stories[13]->parent        = -1;
$stories[13]->plan          = '0';
$stories[13]->demand        = '0';
$stories[13]->type          = 'story';
$stories[13]->path          = ',13,';
$stories[13]->parentVersion = '0';
$stories[14]                = new stdclass();
$stories[14]->id            = 14;
$stories[14]->parent        = 13;
$stories[14]->plan          = '0';
$stories[14]->demand        = '0';
$stories[14]->type          = 'story';
$stories[14]->path          = ',13,14,';
$stories[14]->parentVersion = '1';
$stories[15]                = new stdclass();
$stories[15]->id            = 15;
$stories[15]->parent        = 11;
$stories[15]->plan          = '0';
$stories[15]->demand        = '0';
$stories[15]->type          = 'story';
$stories[15]->path          = ',11,15,';
$stories[15]->parentVersion = '1';

r($storyModel->mergePlanTitleAndChildren(0, array())) && p() && e('0'); //不传入任何数据。
r($storyModel->mergePlanTitleAndChildren(1, array())) && p() && e('0'); //只传入产品。

$mergedStories = $storyModel->mergePlanTitleAndChildren(1, $stories, 'requirement');
r($mergedStories[12]->planTitle)          && p() && e('计划1'); // 传入产品和需求，查看planTitle字段。
r(isset($mergedStories[13]->children[3])) && p() && e('0');     // 传入产品和需求，查看子需求是否存在。
r($mergedStories[15]->originParent)       && p() && e('11');    // 传入产品和需求，查看originParent字段。
