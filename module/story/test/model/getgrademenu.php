#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getGradeMenu();
timeout=0
cid=18535

- 执行storyTest模块的getGradeMenuTest方法，参数是'story'
 - 第0条的text属性 @查看
 - 第0条的value属性 @story
- 执行storyTest模块的getGradeMenuTest方法，参数是'epic'
 - 第0条的text属性 @查看
 - 第0条的value属性 @epic
- 执行storyTest模块的getGradeMenuTest方法，参数是'requirement'
 - 第0条的text属性 @查看
 - 第0条的value属性 @requirement
- 执行storyTest模块的getGradeMenuTest方法，参数是'all'  @3
- 执行storyTest模块的getGradeMenuTest方法，参数是'story',
 - 第0条的text属性 @查看
 - 第0条的value属性 @story

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('storygrade')->loadYaml('storygrade_getgrademenu', false, 2)->gen(6);

su('admin');

$storyTest = new storyTest();

r($storyTest->getGradeMenuTest('story')) && p('0:text,value') && e('查看,story');
r($storyTest->getGradeMenuTest('epic')) && p('0:text,value') && e('查看,epic');
r($storyTest->getGradeMenuTest('requirement')) && p('0:text,value') && e('查看,requirement');
r(count($storyTest->getGradeMenuTest('all'))) && p() && e('3');
r($storyTest->getGradeMenuTest('story', (object)array('storyType' => 'story'))) && p('0:text,value') && e('查看,story');