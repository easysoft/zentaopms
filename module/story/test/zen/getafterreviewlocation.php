#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterReviewLocation();
timeout=0
cid=18679

- 执行storyTest模块的getAfterReviewLocationTest方法，参数是1, 'story', 'project'  @getafterreviewlocation.php?m=execution&f=storyView&storyID=1
- 执行storyTest模块的getAfterReviewLocationTest方法，参数是2, 'story', 'project'  @getafterreviewlocation.php?m=projectstory&f=view&storyID=2&projectID=6
- 执行storyTest模块的getAfterReviewLocationTest方法，参数是3, 'story', ''  @getafterreviewlocation.php?m=story&f=view&storyID=3&version=0&param=0&storyType=story
- 执行storyTest模块的getAfterReviewLocationTest方法，参数是4, 'story', 'execution'  @getafterreviewlocation.php?m=execution&f=storyView&storyID=4
- 执行storyTest模块的getAfterReviewLocationTest方法，参数是5, 'epic', ''  @getafterreviewlocation.php?m=epic&f=view&storyID=5&version=0&param=0&storyType=epic

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zendata('story')->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{5},sprint{5}');
$project->multiple->range('0{5},1{5}');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(10);

su('admin');

$storyTest = new storyZenTest();

global $app;

// 步骤1:测试from='project',项目为非多项目类型,评审后跳转
$app->session->project = 1;
r($storyTest->getAfterReviewLocationTest(1, 'story', 'project')) && p() && e('getafterreviewlocation.php?m=execution&f=storyView&storyID=1');

// 步骤2:测试from='project',项目为多项目类型,评审后跳转
$app->session->project = 6;
r($storyTest->getAfterReviewLocationTest(2, 'story', 'project')) && p() && e('getafterreviewlocation.php?m=projectstory&f=view&storyID=2&projectID=6');

// 步骤3:测试from='',story类型需求评审后跳转
r($storyTest->getAfterReviewLocationTest(3, 'story', '')) && p() && e('getafterreviewlocation.php?m=story&f=view&storyID=3&version=0&param=0&storyType=story');

// 步骤4:测试from='execution',评审后跳转
r($storyTest->getAfterReviewLocationTest(4, 'story', 'execution')) && p() && e('getafterreviewlocation.php?m=execution&f=storyView&storyID=4');

// 步骤5:测试from='',epic类型需求评审后跳转
r($storyTest->getAfterReviewLocationTest(5, 'epic', '')) && p() && e('getafterreviewlocation.php?m=epic&f=view&storyID=5&version=0&param=0&storyType=epic');