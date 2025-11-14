#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getPairs();
timeout=0
cid=18545

- 获取产品1计划1下的所有需求数量 @4
- 获取产品1计划1下的所有需求数量，包括父需求 @4
- 获取产品2计划2下的所有需求数量 @0
- 查看通过产品1计划1获取的软件需求属性1 @用户需求1
- 查看通过产品1计划1获取的软件需求属性2 @软件需求2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->gen(5);
zenData('story')->gen(100);

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getPairs(1, 1);
$stories2 = $tester->story->getPairs(1, 1, 'title', true);
$stories3 = $tester->story->getPairs(2, 2);

r(count($stories1)) && p()     && e('4');         // 获取产品1计划1下的所有需求数量
r(count($stories2)) && p()     && e('4');         // 获取产品1计划1下的所有需求数量，包括父需求
r(count($stories3)) && p()     && e('0');         // 获取产品2计划2下的所有需求数量
r($stories1)        && p('1')  && e('用户需求1'); // 查看通过产品1计划1获取的软件需求
r($stories1)        && p('2')  && e('软件需求2'); // 查看通过产品1计划1获取的软件需求