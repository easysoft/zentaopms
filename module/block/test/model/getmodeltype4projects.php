#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $project = zenData('project');
    $project->id->range('1-10');
    $project->model->range('scrum{2},kanban{1},waterfall{2},waterfallplus{1},agileplus{1},[]{1}');
    $project->type->range('project{8}');
    $project->name->range('敏捷项目1,敏捷项目2,看板项目1,瀑布项目1,瀑布项目2,瀑布增强项目1,敏捷增强项目1,普通项目1');
    $project->code->range('AGILE001,AGILE002,KANBAN001,WATER001,WATER002,WATERPLUS001,AGILEPLUS001,NORMAL001');
    $project->status->range('wait{2},doing{4},suspended{1},closed{1}');
    $project->acl->range('open{6},private{2}');
    $project->openedBy->range('admin');
    $project->openedDate->range('2024-01-01 00:00:00');
    $project->deleted->range('0');
    $project->gen(8);
}

/**

title=测试 blockModel::getModelType4Projects();
timeout=0
cid=0

- 测试只有scrum类型项目的情况 @scrum
- 测试只有waterfall类型项目的情况 @waterfall
- 测试混合scrum和waterfall类型项目的情况 @all
- 测试包含kanban类型项目的情况 @scrum
- 测试空项目列表的情况 @~~

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getModelType4Projects(array(1, 2))) && p() && e('scrum');        // 测试只有scrum类型项目的情况
r($tester->block->getModelType4Projects(array(4, 5))) && p() && e('waterfall');   // 测试只有waterfall类型项目的情况
r($tester->block->getModelType4Projects(array(1, 4))) && p() && e('all');          // 测试混合scrum和waterfall类型项目的情况
r($tester->block->getModelType4Projects(array(1, 3))) && p() && e('scrum');        // 测试包含kanban类型项目的情况
r($tester->block->getModelType4Projects(array())) && p() && e('~~');               // 测试空项目列表的情况