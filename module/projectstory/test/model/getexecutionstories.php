#!/usr/bin/env php
<?php

/**

title=测试 projectstoryModel->getExecutionStories();
timeout=0
cid=17978

- 这里取出了id13的项目下需求id10名称第10条的title属性 @软件需求10
- 这里取出了id13的项目下需求id12名称第12条的title属性 @软件需求12
- 这里取出了id14的项目下需求id14名称第14条的title属性 @软件需求14
- 这里取出了id14的项目下需求id16名称第16条的title属性 @软件需求16
- 当项目id不存在时第10条的title属性 @0
- 当需求id不存在时第1000条的title属性 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectstory.unittest.class.php';
su('admin');

zenData('story')->gen(20);
zenData('projectstory')->gen(20);
$project = zenData('project');
$project->type->range('program{10},sprint{10}');
$project->gen(20);

$projectstory = new projectstoryTest();

$projectID  = array();
$projectID[0] = 3;
$projectID[1] = 4;
$projectID[2] = 1000;

$storyID    = array();
$storyID[0] = array(10, 12);
$storyID[1] = array(14, 16);
$storyID[2] = array(1000, 1001);

r($projectstory->getExecutionStoriesTest($projectID[0], $storyID[0])) && p('10:title')   && e('软件需求10'); //这里取出了id13的项目下需求id10名称
r($projectstory->getExecutionStoriesTest($projectID[0], $storyID[0])) && p('12:title')   && e('软件需求12'); //这里取出了id13的项目下需求id12名称
r($projectstory->getExecutionStoriesTest($projectID[1], $storyID[1])) && p('14:title')   && e('软件需求14'); //这里取出了id14的项目下需求id14名称
r($projectstory->getExecutionStoriesTest($projectID[1], $storyID[1])) && p('16:title')   && e('软件需求16'); //这里取出了id14的项目下需求id16名称
r($projectstory->getExecutionStoriesTest($projectID[2], $storyID[0])) && p('10:title')   && e('0');          //当项目id不存在时
r($projectstory->getExecutionStoriesTest($projectID[0], $storyID[2])) && p('1000:title') && e('0');          //当需求id不存在时
