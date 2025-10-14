#!/usr/bin/env php
<?php

/**

title=创建融合瀑布项目测试
timeout=0
cid=73

- 校验项目名称不能为空
 - 测试结果 @创建融合瀑布项目表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 校验计划完成时间不能为空
 - 测试结果 @创建融合瀑布项目表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 创建长期瀑布项目最终测试状态 @SUCCESS
- 校验项目名称不能重复测试结果 @创建融合瀑布项目表单页提示信息正确
- 创建指定计划完成的融合瀑布项目最终测试状态 @SUCCESS
- 创建指定计划完成的项目型融合瀑布项目最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/createwaterfallplus.ui.class.php';

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0');
$project->type->range('program, project');
$project->model->range('[], waterfallplus');
$project->path->range('`,1,`, `,2,`');
$project->grade->range('1');
$project->name->range('项目集A, 融合瀑布项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->begin->range('(-96w)-(-95w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+96w)-(+97w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(2);

$product = zenData('product');
$product->id->range('1-2');
$product->program->range('1, 0');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-2');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$tester = new createWaterfallPlusTester();
$tester->login();

$waterfallPlus = array(
    array('name' => '', 'longTime' => 'longTime', 'PM' => 'admin'),
    array('name' => '融合瀑布项目h01', 'end' => ''),
    array('parent' => '项目集A', 'name' => '融合瀑布项目h01', 'type' => 1, 'end' => '2026-01-31', 'product' => '项目集A/产品1'),
    array('name' => '融合瀑布项目1', 'type' => 1, 'longTime' => 'longTime'),
    array('name' => '融合瀑布项目h02', 'type' => 1, 'end' => '2026-06-30', 'PM' => 'admin'),
    array('name' => '项目型融合瀑布项目h01', 'type' => 0, 'end' => '2026-12-31', 'PM' => 'admin'),
);

r($tester->checkInput($waterfallPlus['0'])) && p('message,status') && e('创建融合瀑布项目表单页提示信息正确, SUCCESS'); // 校验项目名称不能为空
r($tester->checkInput($waterfallPlus['1'])) && p('message,status') && e('创建融合瀑布项目表单页提示信息正确, SUCCESS'); // 校验计划完成时间不能为空
r($tester->checkInput($waterfallPlus['2'])) && p('status')         && e('SUCCESS');                                     // 创建有所属项目集的融合瀑布项目
r($tester->checkInput($waterfallPlus['3'])) && p('message')        && e('创建融合瀑布项目表单页提示信息正确');          // 校验项目名称不能重复
r($tester->checkInput($waterfallPlus['4'])) && p('status')         && e('SUCCESS');                                     // 创建指定计划完成的融合瀑布项目
r($tester->checkInput($waterfallPlus['5'])) && p('status')         && e('SUCCESS');                                     // 创建指定计划完成的项目型融合瀑布项目

$tester->closeBrowser();
