#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getById();
timeout=0
cid=18503

- 获取ID为1、版本号为1的需求的名称。属性title @用户需求版本一1
- 获取ID为1、版本号为2的需求的名称。
 - 属性title @用户需求版本一2
 - 属性spec @这是一个软件需求描述2
- 获取ID为2、版本号为3的需求关联的执行。 @22|30|37
- 获取ID为2、版本号为3的需求关联的需求。 @4
- 获取ID为2、版本号为3的需求，执行ID为21的创建任务数。 @2
- 获取ID为2、版本号为3的需求，执行ID为26的创建任务数。 @2
- 获取ID为2、版本号为3的需求关联的计划。 @1.0
- 获取ID为2、版本号为3的需求的名称。属性title @用户需求版本一6
- 获取ID为10、版本号为3的需求转化Bug的名称。属性toBugTitle @BUG1
- 获取ID为18、版本号为3的需求子需求的标题。属性title @0
- 获取ID为20、版本号为3的需求父需求的标题。属性parentName @软件需求18
- 获取ID为20、版本号为3的需求孪生需求关联执行中的任务数。 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->product->range(1);
$story->plan->range('0,1,0{100}');
$story->duplicateStory->range('0,4,0{100}');
$story->linkStories->range('0,6,0{100}');
$story->linkRequirements->range('3,0{100}');
$story->toBug->range('0{9},1,0{100}');
$story->parent->range('0{17},0{2},18,0{100}');
$story->isParent->range('0{17},1,0{100}');
$story->twins->range('``{27},30,``,28');
$story->gen(30);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-30{3}');
$storySpec->version->range('1-3');
$storySpec->gen(90);

$project = zenData('project');
$project->project->range('0{10},1-10,11-20{6}');
$project->parent->range('0{10},1-10,11-20{6}');
$project->type->range('program{10},project{10},sprint{60}');
$project->gen(80)->fixPath();

$projectStory = zenData('projectstory');
$projectStory->project->range('11-17{6},21-40{2}');
$projectStory->product->range('1');
$projectStory->story->range('2-30:2');
$projectStory->gen(80);

$task = zenData('task');
$task->story->range('2-30:2{2}');
$task->project->range('11-17{6}');
$task->execution->range('21-30{2}');
$task->storyVersion->range('3');
$task->gen(60);

zenData('storystage')->gen(30);
zenData('bug')->gen(1);
zenData('productplan')->gen(1);

$story = new storyModelTest();
$story1Version1  = $story->getByIdTest(1, 1);
$story1Version2  = $story->getByIdTest(1, 2);
$story2Version3  = $story->getByIdTest(2, 3);
$story10Version3 = $story->getByIdTest(10, 3);
$story18Version3 = $story->getByIdTest(18, 3);
$story20Version3 = $story->getByIdTest(20, 3);
$story28Version3 = $story->getByIdTest(28, 3);

r($story1Version1)  && p('title') && e('用户需求版本一1');                            //获取ID为1、版本号为1的需求的名称。
r($story1Version2)  && p('title,spec') && e('用户需求版本一2,这是一个软件需求描述2'); //获取ID为1、版本号为2的需求的名称。

r(implode('|', array_keys($story2Version3->executions)))   && p()        && e('22|30|37');        // 获取ID为2、版本号为3的需求关联的执行。
r(implode('|', array_keys($story2Version3->extraStories))) && p()        && e('4');               // 获取ID为2、版本号为3的需求关联的需求。
r(count($story2Version3->tasks[21]))                       && p()        && e('2');               // 获取ID为2、版本号为3的需求，执行ID为21的创建任务数。
r(count($story2Version3->tasks[26]))                       && p()        && e('2');               // 获取ID为2、版本号为3的需求，执行ID为26的创建任务数。
r($story2Version3->planTitle[1])                           && p()        && e('1.0');             // 获取ID为2、版本号为3的需求关联的计划。
r($story2Version3)                                         && p('title') && e('用户需求版本一6'); // 获取ID为2、版本号为3的需求的名称。

r($story10Version3)                   && p('toBugTitle') && e('BUG1');       // 获取ID为10、版本号为3的需求转化Bug的名称。
r($story18Version3->children)         && p()             && e('0');          // 获取ID为18、版本号为3的需求子需求的标题。
r($story20Version3)                   && p('parentName') && e('软件需求18'); // 获取ID为20、版本号为3的需求父需求的标题。
r(count($story28Version3->tasks[30])) && p()             && e('2');          // 获取ID为20、版本号为3的需求孪生需求关联执行中的任务数。
