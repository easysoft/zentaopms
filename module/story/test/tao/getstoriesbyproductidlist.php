#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getStoriesByProductIdList();
timeout=0
cid=18646

- 获取产品1下的需求
 - 第0条的id属性 @1
 - 第0条的product属性 @1
- 获取产品1，2下的需求
 - 第0条的id属性 @1
 - 第0条的product属性 @1
 - 第4条的id属性 @5
 - 第4条的product属性 @2
- 获取产品1，2下的研发需求
 - 第0条的id属性 @2
 - 第0条的product属性 @1
 - 第1条的id属性 @4
 - 第1条的product属性 @1
- 获取产品1，2下的研发需求
 - 第0条的id属性 @1
 - 第0条的product属性 @1
 - 第1条的id属性 @3
 - 第1条的product属性 @1
- 获取产品1，2下的研发需求
 - 第0条的id属性 @1
 - 第0条的product属性 @1
 - 第1条的id属性 @2
 - 第1条的product属性 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('story')->gen(10);

global $tester;
$tester->loadModel('story');

$productIdList = array('1');
r($tester->story->getStoriesByProductIdList($productIdList)) && p("0:id,product") && e('1,1');                                 //获取产品1下的需求

$productIdList = array('1', '2');
r($tester->story->getStoriesByProductIdList($productIdList)) && p("0:id,product;4:id,product") && e('1,1;5,2');                //获取产品1，2下的需求
r($tester->story->getStoriesByProductIdList($productIdList, 'story')) && p("0:id,product;1:id,product") && e('2,1;4,1');       //获取产品1，2下的研发需求
r($tester->story->getStoriesByProductIdList($productIdList, 'requirement')) && p("0:id,product;1:id,product") && e('1,1;3,1'); //获取产品1，2下的研发需求
r($tester->story->getStoriesByProductIdList($productIdList, '', false)) && p("0:id,product;1:id,product") && e('1,1;2,1');     //获取产品1，2下的研发需求