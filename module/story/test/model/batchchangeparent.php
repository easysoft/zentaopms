#!/usr/bin/env php
<?php

/**

title=测试 storyModel::batchChangeParent();
timeout=0
cid=18468

- 执行storyTest模块的batchChangeParentTest方法，参数是'6, 7', 1, 'story'  @
- 执行storyTest模块的batchChangeParentTest方法，参数是'', 1, 'story'  @~~
- 执行storyTest模块的batchChangeParentTest方法，参数是'8, 9', 999, 'story'  @
- 执行storyTest模块的batchChangeParentTest方法，参数是'1', 1, 'story'  @#1需求的父需求不能为其本身或其子需求，本次修改已将其忽略。
- 执行storyTest模块的batchChangeParentTest方法，参数是'3, 4', 2, 'story'  @

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

r($storyTest->batchChangeParentTest('6,7', 1, 'story')) && p() && e('');
r($storyTest->batchChangeParentTest('', 1, 'story')) && p() && e('~~');
r($storyTest->batchChangeParentTest('8,9', 999, 'story')) && p() && e('');
r($storyTest->batchChangeParentTest('1', 1, 'story')) && p() && e('#1需求的父需求不能为其本身或其子需求，本次修改已将其忽略。');
r($storyTest->batchChangeParentTest('3,4', 2, 'story')) && p() && e('');