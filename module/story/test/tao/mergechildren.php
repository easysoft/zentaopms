#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

zdTable('story')->gen(20);
$relation = zdTable('relation');
$relation->AID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18,9,19');
$relation->BID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8,19,9');
$relation->gen(18);

/**

title=测试 storyModel->mergeChildren();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->mergeChildren(array())) && p() && e('0');

$stories[12]         = new stdclass();
$stories[12]->id     = 12;
$stories[12]->parent = 0;
$stories[13]         = new stdclass();
$stories[13]->id     = 13;
$stories[13]->parent = -1;
$stories[14]         = new stdclass();
$stories[14]->id     = 14;
$stories[14]->parent = 13;
$stories = $storyModel->mergeChildren($stories);
r((!isset($stories[12]->children) and isset($stories[13]->children) and !isset($stories[14]))) && p() && e('1');

$stories[2] = new stdclass();
$stories[2]->id = 2;
$stories[2]->parent = 0;
$stories[10] = new stdclass();
$stories[10]->id = 10;
$stories[10]->parent = 0;
$stories = $storyModel->mergeChildren($stories, 'requirement');
r((isset($stories[2]->children) and !isset($stories[10]->children))) && p() && e('1');
