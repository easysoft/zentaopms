#!/usr/bin/env php
<?php

/**

title=测试 storyModel::updateLinkedCommits();
timeout=0
cid=18594

- 执行storyTest模块的updateLinkedCommitsTest方法，参数是1, 1, array  @1
- 执行storyTest模块的updateLinkedCommitsTest方法，参数是0, 1, array  @1
- 执行storyTest模块的updateLinkedCommitsTest方法，参数是1, 0, array  @1
- 执行storyTest模块的updateLinkedCommitsTest方法，参数是1, 1, array  @1
- 执行storyTest模块的updateLinkedCommitsTest方法，参数是999, 1, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('story')->loadYaml('story')->gen(10);
zenData('product')->loadYaml('product')->gen(5);
zenData('relation')->gen(0);

su('admin');

$storyTest = new storyTest();

r($storyTest->updateLinkedCommitsTest(1, 1, array(123, 456))) && p() && e(1);
r($storyTest->updateLinkedCommitsTest(0, 1, array(123))) && p() && e(1);
r($storyTest->updateLinkedCommitsTest(1, 0, array(123))) && p() && e(1);
r($storyTest->updateLinkedCommitsTest(1, 1, array())) && p() && e(1);
r($storyTest->updateLinkedCommitsTest(999, 1, array(123))) && p() && e(1);