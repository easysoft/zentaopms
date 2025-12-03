#!/usr/bin/env php
<?php

/**

title=- 执行storyTest模块的batchChangeParentTest方法，参数是'1', 1, 'story'  @
timeout=0
cid=1

- 测试批量修改父需求 @0
- 测试批量修改父需求 @0
- 测试批量修改父需求 @0
- 测试批量修改父需求 @0
- 测试批量修改父需求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('story')->gen(10);
zenData('storygrade')->gen(6);
zenData('storyspec')->gen(10);

su('admin');

$storyTest = new storyTest();

r($storyTest->batchChangeParentTest('6,7', 1, 'story')) && p() && e('0'); // 测试批量修改父需求
r($storyTest->batchChangeParentTest('', 1, 'story')) && p() && e('0'); // 测试批量修改父需求
r($storyTest->batchChangeParentTest('8,9', 999, 'story')) && p() && e('0'); // 测试批量修改父需求
r($storyTest->batchChangeParentTest('1', 1, 'story')) && p() && e('0'); // 测试批量修改父需求
r($storyTest->batchChangeParentTest('3,4', 2, 'story')) && p() && e('0'); // 测试批量修改父需求