#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateTwins();
timeout=0
cid=18664

- 不传入需求，也不传入产品。 @0
- 传入需求列表，检查twins字段。
 - 属性1 @:2:3:4:
 - 属性2 @:1:3:4:
 - 属性3 @:1:2:4:
 - 属性4 @:1:2:3:

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('story')->gen(5);

$storyTest = new storyTest();

r($storyTest->updateTwinsTest(array(), 1)) && p() && e('0'); //不传入需求，也不传入产品。
r($storyTest->updateTwinsTest(array(1 => 1, 2 => 2, 3 => 3, 4 => 4), 1)) && p('1,2,3,4') && e(':2:3:4:,:1:3:4:,:1:2:4:,:1:2:3:'); //传入需求列表，检查twins字段。