#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

zdTable('story')->gen(5);

/**

title=测试 storyModel->updateTwins();
cid=1
pid=1

*/

$storyTest = new storyTest();

r($storyTest->updateTwinsTest(array())) && p() && e('0'); //不传入需求，也不传入产品。
r($storyTest->updateTwinsTest(array(1 => 1, 2 => 2, 3 => 3))) && p('1,2,3') && e('2:3,1:3,1:2'); //传入需求列表，检查twins字段。
