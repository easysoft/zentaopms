#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

function initData()
{
    $project = zenData('project');
    $project->id->range('11-19,101-109');
    $project->project->range('11-19');
    $project->name->setFields(array(
        array('field' => 'name1', 'range' => '项目{9},迭代{9}'),
        array('field' => 'name2', 'range' => '11-19,101-109')
    ));
    $project->code->setFields(array(
        array('field' => 'name1', 'range' => 'project{9},execution{9}'),
        array('field' => 'name2', 'range' => '11-19,101-109')
    ));
    $project->model->range("scrum{9},[]{9}");
    $project->auth->range("[]");
    $project->path->range("[]");
    $project->type->range("project{9},sprint{9}");
    $project->grade->range("1");
    $project->days->range("1");
    $project->status->range("wait,doing,suspended,closed");
    $project->desc->range("[]");
    $project->budget->range("100000,200000");
    $project->budgetUnit->range("CNY");
    $project->percent->range("0-0");
    $project->openedDate->range("`2023-05-01 10:00:10`");
    $project->gen(18);

    zenData('task')->gen(10);
}

/**

title=测试 projectModel::fetchProjectList();
timeout=0
cid=1


*/

initData();
$noneIDList  = array();
$wrongIDList = array('1',  '2');
$realIDList  = array('13', '14');

global $tester;
$projectTester = $tester->loadModel('project');
r($projectTester->fetchTaskEstimateByIdList($noneIDList))  && p()              && e('0'); // 查询没有项目ID的情况
r($projectTester->fetchTaskEstimateByIdList($wrongIDList)) && p()              && e('0'); // 查询错误ID的情况
r($projectTester->fetchTaskEstimateByIdList($realIDList))  && p('14:estimate') && e('3'); // 查询正常ID的情况
