#!/usr/bin/env php
<?php

/**

title=测试 storyModel::relieveTwins();
timeout=0
cid=18579

- 执行storyTest模块的relieveTwinsTest方法，参数是1, 2  @1
- 执行storyTest模块的relieveTwinsTest方法，参数是999, 2  @1
- 执行storyTest模块的relieveTwinsTest方法，参数是1, 999  @1
- 执行storyTest模块的relieveTwinsTest方法，参数是0, 0  @1
- 执行storyTest模块的relieveTwinsTest方法，参数是-1, -1  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1-3');
$table->title->range('需求标题1,需求标题2,需求标题3');
$table->twins->range(',2,3,,4,5,,,1,2,3,');
$table->status->range('active{5},closed{3},draft{2}');
$table->gen(10);

su('admin');

$storyTest = new storyModelTest();

r($storyTest->relieveTwinsTest(1, 2)) && p() && e('1');
r($storyTest->relieveTwinsTest(999, 2)) && p() && e('1');
r($storyTest->relieveTwinsTest(1, 999)) && p() && e('1');
r($storyTest->relieveTwinsTest(0, 0)) && p() && e('1');
r($storyTest->relieveTwinsTest(-1, -1)) && p() && e('1');