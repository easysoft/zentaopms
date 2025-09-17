#!/usr/bin/env php
<?php

/**

title=项目发布导出HTML
timeout=0
cid=73

- 项目发布导出时文件名必填项检查
 - 测试结果 @项目发布导出必填提示信息正确
 - 最终测试状态 @SUCCESS
- 项目发布导出所有数据
 - 测试结果 @项目发布导出成功
 - 最终测试状态 @SUCCESS
- 项目发布导出指定数据
 - 测试结果 @项目发布导出成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/releaseexport.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$system = zenData('system');
$system->id->range('1');
$system->product->range('1');
$system->name->range('应用AAA');
$system->status->range('active');
$system->integrated->range('0');
$system->createdBy->range('admin');
$system->gen(1);

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

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1');
$projectProduct->gen(1);

$release = zenData('release');
$release->id->range('1');
$release->project->range('1');
$release->product->range('1');
$release->branch->range('0');
$release->name->range('release1');
$release->system->range('1');
$release->status->range('wait');
$release->stories->range('[]');
$release->bugs->range('[]');
$release->desc->range('描述111');
$release->deleted->range('0');
$release->gen(1);

$tester = new releaseExportTester();
$tester->login();

//设置导出发布数据
$release = array(
    array('filename' => '导出文件1'),
    array('filename' => '导出文件2', 'exportdata' => '解决的Bug'),
);

r($tester->exportWithNoFilename())       && p('message,status') && e('项目发布导出必填提示信息正确,SUCCESS');   // 项目发布导出时文件名必填项检查
r($tester->releaseExport($release['0'])) && p('message,status') && e('项目发布导出成功,SUCCESS');               // 项目发布导出所有数据
r($tester->releaseExport($release['1'])) && p('message,status') && e('项目发布导出成功,SUCCESS');               // 项目发布导出指定数据

$tester->closeBrowser();
