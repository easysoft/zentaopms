#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

function initData()
{
    $project = zenData('project');
    $project->id->range('1-10,11-19');
    $project->project->range('0{10},11-19');
    $project->name->setFields(array(
        array('field' => 'name1', 'range' => '项目集{10},项目{9}'),
        array('field' => 'name2', 'range' => '1-10,11-19')
    ));
    $project->code->setFields(array(
        array('field' => 'name1', 'range' => 'program{10},project{9}'),
        array('field' => 'name2', 'range' => '1-10,11-19')
    ));
    $project->path->setFields(array(
        array('field' => 'name1', 'range' => '1-10', 'prefix' => ','),
        array('field' => 'name2', 'range' => '11-19', 'prefix' => ',', 'postfix' => ',')
    ));
    $project->model->range("[]{10},scrum{9}");
    $project->auth->range("[]");
    $project->type->range("program{10},project{9}");
    $project->grade->range("1{10},2{9}");
    $project->parent->range("0{10},1-9");
    $project->days->range("1");
    $project->status->range("wait,doing,suspended,closed");
    $project->desc->range("[]");
    $project->budget->range("100000,200000");
    $project->budgetUnit->range("CNY");
    $project->percent->range("0-0");
    $project->openedDate->range("`2023-05-01 10:00:10`");
    $project->gen(18);

    zenData('user')->gen(10);
}

/**

title=测试 projectTao::getParentProgram();
cid=17911


*/

initData();
su('admin');

global $tester;
$projectTester = $tester->loadModel('project');

$pathList  = array('', '1', ',1,11,');
$gradeList = array(0, 1, 2);

r($projectTester->getParentProgram($pathList[0], $gradeList[0])) && p() && e('0');       // 路径和层级都错误的情况
r($projectTester->getParentProgram($pathList[0], $gradeList[1])) && p() && e('0');       // 路径错误，层级正确的情况
r($projectTester->getParentProgram($pathList[1], $gradeList[1])) && p() && e('0');       // 路径正确，层级错误的情况
r($projectTester->getParentProgram($pathList[1], $gradeList[2])) && p() && e('项目集1'); // 路径正确，层级正确的情况
r($projectTester->getParentProgram($pathList[2], $gradeList[2])) && p() && e('项目集1'); // 路径有逗号，层级正确的情况
