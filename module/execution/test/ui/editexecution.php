#!/usr/bin/env php
<?php

/**

title=编辑执行
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/editexecution.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->storyType->range('story, []');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('敏捷项目, 执行');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(2);

$tester = new editExecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'name'     => '编辑测试执行1',
        'project'  => '敏捷项目',
        'begin'    => date('Y-m-d', strtotime('+1 days')),
        'end'      => date('Y-m-d', strtotime('+3 days')),
        'products' => '',
    ),
);

r($tester->edit($execution['0'])) && p('status,message') && e('SUCCESS,编辑执行成功'); //编辑执行成功
$tester->closeBrowser();
