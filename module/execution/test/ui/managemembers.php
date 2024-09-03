<?php

/**
title=维护团队成员
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/managemembers.ui.class.php';

$project = zenData('project');
project->id->range('1-2');
$project->project->range('0');
$project->model->range('scrum{2}');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('项目1, 无产品项目2');
$project->path->range('`,1,`, `,2,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(2);

$execution = zenData('project');
$execution->id->range('5-7');
$execution->project->range('1, 1, 2');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1, 1, 2');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2, 项目2迭代1');
$execution->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(3, false);
