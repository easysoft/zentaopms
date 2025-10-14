#!/usr/bin/env php
<?php

/**

title=运营管理界面延期看板
timeout=0
cid=1

- 未开始的执行，修改可用工作日，保存成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行成功
- 进行中的执行，修改可用工作日，保存成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行成功
- 未开始的执行，延期弹窗中修改正确的起止日期后点击保存
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行成功
- 进行中的执行，延期弹窗中修改正确的起止日期后点击保存
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行成功
- 计划结束日期为空，可用工日不为空
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行表单页提示信息正确
- 计划开始日期为空
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行表单页提示信息正确
- 计划开始日期小于项目计划开始日期
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行表单页提示信息正确
- 计划结束日期大于项目计划完成日期
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行表单页提示信息正确
- 可用工日为空
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行表单页提示信息正确
- 可用工日为非数字
 - 最终测试状态 @SUCCESS
 - 测试结果 @延期执行表单页提示信息正确

 */

chdir(__DIR__);
include '../lib/ui/putoffinlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('[]');
$project->storyType->range('[]');
$project->parent->range('0');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('项目');
$project->hasProduct->range('0');
$project->begin->range('(-6M)-(-5M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5M)-(+6M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->vision->range('lite');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('2, 3');
$execution->project->range('1{2}');
$execution->model->range('[]{2}');
$execution->type->range('kanban{2}');
$execution->auth->range('[]');
$execution->storyType->range('[]');
$execution->parent->range('1{2}');
$execution->path->range('`,1,2,`, `,1,3,`');
$execution->grade->range('1');
$execution->name->range('看板1, 看板2');
$execution->hasProduct->range('0');
$execution->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->openedBy->range('user1');
$execution->acl->range('open');
$execution->status->range('wait, doing');
$execution->vision->range('lite');
$execution->gen(2, false);

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('项目');
$product->shadow->range('1');
$product->bind->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(3);

$tester = new putoffexecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'days'  => '10',
    ),
    '1' => array(
        'begin' => date('Y-m-d', strtotime('-1 days')),
        'end'   => date('Y-m-d', strtotime('+1 days')),
        'days'  => '10',
    ),
    '2' => array(
        'begin' => '',
        'end'   => date('Y-m-d'),
        'days'  => '10',
    ),
    '3' =>array(
        'begin' => date('Y-m-d'),
        'end'   => '',
        'days'  => '10',
    ),
    '4' => array(
        'begin' => date('Y-m-d', strtotime('-10 months')),
        'end'   => date('Y-m-d'),
        'days'  => '10',
    ),
    '5' => array(
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+10 months')),
        'days'  => '10',
    ),
    '6' => array(
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d'),
        'days'  => '',
    ),
    '7' => array(
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d'),
        'days'  => 'aaa',
    ),
);

r($tester->putoff($execution['0'], '2'))                       && p('status,message') && e('SUCCESS,延期执行成功');               //未开始的执行，修改可用工作日，保存成功
r($tester->putoff($execution['0'], '3'))                       && p('status,message') && e('SUCCESS,延期执行成功');               //进行中的执行，修改可用工作日，保存成功
r($tester->putoff($execution['1'], '2'))                       && p('status,message') && e('SUCCESS,延期执行成功');               //未开始的执行，延期弹窗中修改正确的起止日期后点击保存
r($tester->putoff($execution['1'], '3'))                       && p('status,message') && e('SUCCESS,延期执行成功');               //进行中的执行，延期弹窗中修改正确的起止日期后点击保存
r($tester->putoffWithWrongDate($execution['3'], '3', 'end'))   && p('status,message') && e('SUCCESS,延期执行表单页提示信息正确'); //计划结束日期为空，可用工日不为空
r($tester->putoffWithWrongDate($execution['2'], '2', 'begin')) && p('status,message') && e('SUCCESS,延期执行表单页提示信息正确'); //计划开始日期为空
r($tester->putoffWithWrongDate($execution['4'], '3', 'begin')) && p('status,message') && e('SUCCESS,延期执行表单页提示信息正确'); //计划开始日期小于项目计划开始日期
r($tester->putoffWithWrongDate($execution['5'], '2', 'end'))   && p('status,message') && e('SUCCESS,延期执行表单页提示信息正确'); //计划结束日期大于项目计划完成日期
r($tester->putoffWithWrongDays($execution['6'], '2'))          && p('status,message') && e('SUCCESS,延期执行表单页提示信息正确'); //可用工日为空
r($tester->putoffWithWrongDays($execution['7'], '2'))          && p('status,message') && e('SUCCESS,延期执行表单页提示信息正确'); //可用工日为非数字
$tester->closeBrowser();
