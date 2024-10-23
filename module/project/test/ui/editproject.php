#!/usr/bin/env php
<?php

/**

title=编辑瀑布项目测试
timeout=0
cid=73

- 编辑瀑布项目成功  测试结果 @编辑项目成功

*/
chdir(__DIR__);
include '../lib/editproject.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('waterfall');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('瀑布项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->begin->range('(-72w)-(-71w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+72w)-(+73w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$tester = new editProjectTester();
$tester->login();

$project = array(
    array('name' => '编辑项目' . time()),
);

r($tester->editProject($project['0'])) && p('message') && e('编辑项目成功');  //编辑项目名称

$tester->closeBrowser();
