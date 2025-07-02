#!/usr/bin/env php
<?php

/**

title=运营界面项目团队成员列表
timeout=0
cid=1

- 移除项目已有的团队成员测试结果 @项目团队成员移除成功

*/

chdir(__DIR__);
include '../lib/teamforlite.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('运营项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->begin->range('(-72w)-(-71w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+72w)-(+73w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
