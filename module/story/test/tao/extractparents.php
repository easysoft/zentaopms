#!/usr/bin/env php
<?php

/**

title=测试 storyModel->extractParents();
timeout=0
cid=0

- 参数是空数组，查看返回值 @0
- 参数是stories，查看返回值
 -  @8
 - 属性1 @4
 - 属性2 @~~
 - 属性3 @~~
 - 属性4 @~~

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->extractParents(array())) && p() && e('0'); // 参数是空数组，查看返回值

$stories[2] = new stdclass();
$stories[2]->id = 2;
$stories[2]->parent = 0;
$stories[3] = new stdclass();
$stories[3]->id = 3;
$stories[3]->parent = -1;
$stories[4] = new stdclass();
$stories[4]->id = 4;
$stories[4]->parent = 8;
$stories[5] = new stdclass();
$stories[5]->id = 5;
$stories[5]->parent = 4;
r($storyModel->extractParents($stories)) && p('0,1,2,3,4') && e('8,4,~~,~~,~~'); // 参数是stories，查看返回值