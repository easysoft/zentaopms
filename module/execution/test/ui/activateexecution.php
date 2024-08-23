<?php
chdir(__DIR__);
include '../lib/activateexecution.ui.class.php';

zenData('project')->loadYaml('execution', false, 2)->gen(1);
$execution = zenData('project');
$execution->id->range('101,103');
$execution->project->range('11');
$execution->type->range('sprint');
$execution->parent->range('1');
$execution->path->range('`,1,101,`, `,1,103,`');
$execution->grade->range('1');
$execution->name->range('未开始执行, 进行中执行');
$execution->hasProduct->range('1');
$execution->begin->range('(-1M)-(-3W):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+1M)-(+2M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->days->range('10');
$execution->realBegan->range('(-2w)-(-1w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->status->range('wait, doing');
$execution->gen(2, false);
