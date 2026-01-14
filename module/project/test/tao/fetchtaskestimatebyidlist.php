#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/tao.class.php';
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

title=测试 projectModel::fetchTaskEstimateByIdList();
timeout=0
cid=17907

- 查询没有项目ID的情况 @0
- 查询错误ID的情况 @0
- 检查项目13的预计工时属性estimate @2
- 检查项目14的预计工时属性estimate @3
- 检查项目15的预计工时属性estimate @4
- 检查不存在ID的情况 @0

*/

initData();
$noneIDList  = array();
$wrongIDList = array('1',  '2');
$realIDList  = array('13', '14', '15', '1');

global $tester;
$projectTester = $tester->loadModel('project');
r($projectTester->fetchTaskEstimateByIdList($noneIDList))  && p() && e('0'); // 查询没有项目ID的情况
r($projectTester->fetchTaskEstimateByIdList($wrongIDList)) && p() && e('0'); // 查询错误ID的情况

$estimates = $projectTester->fetchTaskEstimateByIdList($realIDList);
r($estimates[13])       && p('estimate') && e('2'); // 检查项目13的预计工时
r($estimates[14])       && p('estimate') && e('3'); // 检查项目14的预计工时
r($estimates[15])       && p('estimate') && e('4'); // 检查项目15的预计工时
r(isset($estimates[1])) && p()           && e('0'); // 检查不存在ID的情况
