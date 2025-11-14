#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getZeroCase();
cid=18570

- 获取产品1下的零用例需求数量 @1
- 获取产品0下的零用例需求数量 @0
- 获取产品1下的零用例需求详情
 - 第4条的title属性 @软件需求4
 - 第4条的status属性 @changing
 - 第4条的type属性 @story
 - 第4条的stage属性 @planned

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('story')->gen(10);
zenData('case')->gen(10);

global $tester;
$tester->loadModel('story');
$zeroCaseStory1 = $tester->story->getZeroCase(1);
$zeroCaseStory3 = $tester->story->getZeroCase(0);

r(count($zeroCaseStory1)) && p() && e('1'); // 获取产品1下的零用例需求数量
r(count($zeroCaseStory3)) && p() && e('0'); // 获取产品0下的零用例需求数量
r($zeroCaseStory1)        && p('4:title,status,type,stage') && e('软件需求4,changing,story,planned');    // 获取产品1下的零用例需求详情
