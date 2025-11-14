#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterEditLocation();
timeout=0
cid=18678

- 执行storyTest模块的getAfterEditLocationTest方法，参数是1, 'story'  @getaftereditlocation.php?m=story&f=view&storyID=1&version=0&param=0&storyType=story
- 执行storyTest模块的getAfterEditLocationTest方法，参数是2, 'requirement'  @getaftereditlocation.php?m=requirement&f=view&storyID=2&version=0&param=0&storyType=requirement
- 执行storyTest模块的getAfterEditLocationTest方法，参数是3, 'story'  @getaftereditlocation.php?m=execution&f=storyView&storyID=3&project=1
- 执行storyTest模块的getAfterEditLocationTest方法，参数是4, 'story'  @getaftereditlocation.php?m=projectstory&f=view&storyID=4&project=6
- 执行storyTest模块的getAfterEditLocationTest方法，参数是5, 'epic'  @getaftereditlocation.php?m=epic&f=view&storyID=5&version=0&param=0&storyType=epic

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

// 步骤1：测试product标签下的story类型需求编辑后跳转
$app->tab = 'product';
r($storyTest->getAfterEditLocationTest(1, 'story')) && p() && e('getaftereditlocation.php?m=story&f=view&storyID=1&version=0&param=0&storyType=story');

// 步骤2：测试product标签下的requirement类型需求编辑后跳转
$app->tab = 'product';
r($storyTest->getAfterEditLocationTest(2, 'requirement')) && p() && e('getaftereditlocation.php?m=requirement&f=view&storyID=2&version=0&param=0&storyType=requirement');

// 步骤3：测试project标签下的story类型需求,项目为非多项目类型,编辑后跳转
$app->tab = 'project';
$app->session->project = 1;
r($storyTest->getAfterEditLocationTest(3, 'story')) && p() && e('getaftereditlocation.php?m=execution&f=storyView&storyID=3&project=1');

// 步骤4：测试project标签下的story类型需求,项目为多项目类型,编辑后跳转
$app->tab = 'project';
$app->session->project = 6;
r($storyTest->getAfterEditLocationTest(4, 'story')) && p() && e('getaftereditlocation.php?m=projectstory&f=view&storyID=4&project=6');

// 步骤5：测试product标签下的epic类型需求编辑后跳转
$app->tab = 'product';
r($storyTest->getAfterEditLocationTest(5, 'epic')) && p() && e('getaftereditlocation.php?m=epic&f=view&storyID=5&version=0&param=0&storyType=epic');