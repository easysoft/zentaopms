#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getPlanStories();
timeout=0
cid=18548

- 执行storyTest模块的getPlanStoriesTest方法，参数是1, 'all', 'id_desc'  @4
- 执行storyTest模块的getPlanStoriesTest方法，参数是1, 'all', 'module, id_desc'  @4
- 执行storyTest模块的getPlanStoriesTest方法，参数是999, 'all', 'id_desc'  @0
- 执行storyTest模块的getPlanStoriesTest方法，参数是1, 'active', 'id_desc'  @1
- 执行storyTest模块的getPlanStoriesTest方法，参数是-1, 'all', 'id_desc'  @0
- 执行storyTest模块的getPlanStoriesTest方法，参数是0, 'all', 'id_desc'  @0
- 执行storyTest模块的getPlanStoriesTest方法，参数是3, 'all', 'id_desc'  @0

*/

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zendata('product')->loadYaml('story_getplanstories', false, 2)->gen(5);
zendata('story')->loadYaml('story_getplanstories', false, 2)->gen(50);
zendata('planstory')->loadYaml('planstory_getplanstories', false, 2)->gen(40);
zendata('module')->loadYaml('module_getplanstories', false, 2)->gen(20);

su('admin');

$storyTest = new storyTest();

r(count($storyTest->getPlanStoriesTest(1, 'all', 'id_desc'))) && p() && e('4');
r(count($storyTest->getPlanStoriesTest(1, 'all', 'module,id_desc'))) && p() && e('4');
r(count($storyTest->getPlanStoriesTest(999, 'all', 'id_desc'))) && p() && e('0');
r(count($storyTest->getPlanStoriesTest(1, 'active', 'id_desc'))) && p() && e('1');
r(count($storyTest->getPlanStoriesTest(-1, 'all', 'id_desc'))) && p() && e('0');
r(count($storyTest->getPlanStoriesTest(0, 'all', 'id_desc'))) && p() && e('0');
r(count($storyTest->getPlanStoriesTest(3, 'all', 'id_desc'))) && p() && e('0');