#!/usr/bin/env php
<?php

/**

title=项目版本关联和移除研发需求
timeout=0
cid=73

- 项目版本关联研发需求
 - 测试结果 @版本关联需求成功
 - 最终测试状态 @SUCCESS
- 单个移除研发需求
 - 测试结果 @单个移除需求成功
 - 最终测试状态 @SUCCESS
- 批量移除研发需求
 - 测试结果 @移除全部需求成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/buildlinkstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->attribute->range('[]');
$project->auth->range('[]');
$project->parent->range('0');
$project->grade->range('1');
$project->name->range('敏捷项目1');
$project->path->range('`,1,`');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('2-3');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2');
$execution->path->range('`,1,2,`, `,1,3,`');
$execution->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(2, false);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1,2,3');
$projectProduct->product->range('1');
$projectProduct->gen(3);

$build = zenData('build');
$build->id->range('1');
$build->project->range('1');
$build->product->range('1');
$build->branch->range('0');
$build->execution->range('2');
$build->name->range('版本1');
$build->stories->range('[]');
$build->bugs->range('[]');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->desc->range('描述111');
$build->deleted->range('0');
$build->gen(1);

$story = zenData('story');
$story->id->range('1-5');
$story->path->range('[]');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->source->range('[]');
$story->title->range('需求001,需求002,需求003,需求004,需求005');
$story->type->range('story');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->reviewedDate->range('`NULL`');
$story->closedBy->range('[]');
