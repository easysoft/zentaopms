#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignStoryGradeData();
timeout=0
cid=0

- 执行docTest模块的assignStoryGradeDataTest方法，参数是'planStory'
 - 属性hasGradeGroup @1
 - 属性hasStoryType @0
- 执行docTest模块的assignStoryGradeDataTest方法，参数是'projectStory'
 - 属性hasGradeGroup @1
 - 属性hasStoryType @0
- 执行docTest模块的assignStoryGradeDataTest方法，参数是'productStory'
 - 属性hasGradeGroup @1
 - 属性hasStoryType @1
 - 属性storyType @story
- 执行docTest模块的assignStoryGradeDataTest方法，参数是'ER'
 - 属性hasGradeGroup @1
 - 属性hasStoryType @1
 - 属性storyType @epic
- 执行docTest模块的assignStoryGradeDataTest方法，参数是'UR'
 - 属性hasGradeGroup @1
 - 属性hasStoryType @1
 - 属性storyType @requirement
- 执行docTest模块的assignStoryGradeDataTest方法，参数是'executionStory' 属性hasGradeGroup @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->assignStoryGradeDataTest('planStory')) && p('hasGradeGroup,hasStoryType') && e('1,0');
r($docTest->assignStoryGradeDataTest('projectStory')) && p('hasGradeGroup,hasStoryType') && e('1,0');
r($docTest->assignStoryGradeDataTest('productStory')) && p('hasGradeGroup,hasStoryType,storyType') && e('1,1,story');
r($docTest->assignStoryGradeDataTest('ER')) && p('hasGradeGroup,hasStoryType,storyType') && e('1,1,epic');
r($docTest->assignStoryGradeDataTest('UR')) && p('hasGradeGroup,hasStoryType,storyType') && e('1,1,requirement');
r($docTest->assignStoryGradeDataTest('executionStory')) && p('hasGradeGroup') && e('1');