#!/usr/bin/env php
<?php

/**

title=项目版本关联和移除Bug
timeout=0
cid=73

- 项目版本关联bug
 - 测试结果 @版本关联bug成功
 - 最终测试状态 @SUCCESS
- 单个移除bug
 - 测试结果 @单个移除bug成功
 - 最终测试状态 @SUCCESS
- 移除全部bug
 - 测试结果 @移除全部bug成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/buildlinkbug.ui.class.php';

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

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->project->range('1');
$bug->product->range('1');
$bug->execution->range('2');
$bug->title->range('Bug1, Bug2, Bug3, Bug4, Bug5');
$bug->status->range('active{2}, resolved{2}, closed{1}');
$bug->assignedTo->range('[]');
$bug->gen(5);

$tester = new buildLinkBugTester();
$tester->login();

r($tester->linkBug())        && p('message,status') && e('版本关联bug成功,SUCCESS');  // 项目版本关联bug
r($tester->unlinkBug())      && p('message,status') && e('单个移除bug成功,SUCCESS');  // 单个移除bug
r($tester->batchUnlinkBug()) && p('message,status') && e('移除全部bug成功,SUCCESS');  // 移除全部bug

$tester->closeBrowser();
