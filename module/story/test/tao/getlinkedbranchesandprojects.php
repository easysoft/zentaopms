#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getLinkedBranchesAndProjects();
cid=18638

- 不传入需求，检查关联的分支。 @0
- 不传入需求，检查关联的项目。 @0
- 传入需求，检查关联的分支。 @0:1:2
- 传入需求，检查 11 是否是项目看板。 @0
- 传入需求，检查 11 项目关联的分支。 @0
- 传入需求，检查 12 是否是项目看板。 @1
- 传入需求，检查 12 项目关联的分支。 @0
- 传入需求，检查 13 是否是项目看板。 @0
- 传入需求，检查 13 项目关联的分支。属性1 @1
- 传入需求，检查 14 是否是项目看板。 @1
- 传入需求，检查 14 项目关联的分支。属性2 @2
- 传入不存在的需求，检查关联的分支。 @0
- 传入不存在的需求，检查关联的项目。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('story')->gen(2);
$project = zenData('project');
$project->id->range('11-20');
$project->type->range('project,project,sprint,kanban');
$project->model->range('scrum,kanban,``{2}');
$project->gen(4);

$projectStory = zenData('projectstory');
$projectStory->story->range('1');
$projectStory->product->range('1');
$projectStory->project->range('11-20');
$projectStory->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->product->range('1');
$projectProduct->project->range('11-20');
$projectProduct->branch->range('0{2},1,2');
$projectProduct->gen(4);

global $tester;
$storyModel = $tester->loadModel('story');
list($linkedBranches, $linkedProjects) = $storyModel->getLinkedBranchesAndProjects(0);
r($linkedBranches) && p() && e('0'); //不传入需求，检查关联的分支。
r($linkedProjects) && p() && e('0'); //不传入需求，检查关联的项目。

list($linkedBranches, $linkedProjects) = $storyModel->getLinkedBranchesAndProjects(1);
r(implode(':', $linkedBranches)) && p()    && e('0:1:2'); //传入需求，检查关联的分支。
r($linkedProjects[11]->kanban)   && p()    && e('0');     //传入需求，检查 11 是否是项目看板。
r($linkedProjects[11]->branches) && p('0') && e('0');     //传入需求，检查 11 项目关联的分支。
r($linkedProjects[12]->kanban)   && p()    && e('1');     //传入需求，检查 12 是否是项目看板。
r($linkedProjects[12]->branches) && p('0') && e('0');     //传入需求，检查 12 项目关联的分支。
r($linkedProjects[13]->kanban)   && p()    && e('0');     //传入需求，检查 13 是否是项目看板。
r($linkedProjects[13]->branches) && p('1') && e('1');     //传入需求，检查 13 项目关联的分支。
r($linkedProjects[14]->kanban)   && p()    && e('1');     //传入需求，检查 14 是否是项目看板。
r($linkedProjects[14]->branches) && p('2') && e('2');     //传入需求，检查 14 项目关联的分支。

list($linkedBranches, $linkedProjects) = $storyModel->getLinkedBranchesAndProjects(10);
r($linkedBranches) && p() && e('0'); //传入不存在的需求，检查关联的分支。
r($linkedProjects) && p() && e('0'); //传入不存在的需求，检查关联的项目。
