#!/usr/bin/env php
<?php

/**

title=测试 projectModel::unlinkStoryByType();
timeout=0
cid=17872

- 执行projectTest模块的unlinkStoryByTypeTest方法，参数是1, 'story'  @0
- 执行projectTest模块的unlinkStoryByTypeTest方法，参数是999, 'story'  @0
- 执行projectTest模块的unlinkStoryByTypeTest方法，参数是1, ''  @0
- 执行projectTest模块的unlinkStoryByTypeTest方法，参数是1, 'story, requirement'  @0
- 执行projectTest模块的unlinkStoryByTypeTest方法，参数是0, 'story'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->loadYaml('project_unlinkstorybytype', false, 2)->gen(10);
zenData('story')->loadYaml('story_unlinkstorybytype', false, 2)->gen(20);
zenData('projectstory')->loadYaml('projectstory_unlinkstorybytype', false, 2)->gen(20);

su('admin');

$projectTest = new projectTest();

r($projectTest->unlinkStoryByTypeTest(1, 'story'))             && p() && e('0');
r($projectTest->unlinkStoryByTypeTest(999, 'story'))           && p() && e('0');
r($projectTest->unlinkStoryByTypeTest(1, ''))                  && p() && e('0');
r($projectTest->unlinkStoryByTypeTest(1, 'story,requirement')) && p() && e('0');
r($projectTest->unlinkStoryByTypeTest(0, 'story'))             && p() && e('0');
