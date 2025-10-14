#!/usr/bin/env php
<?php

/**

title=编辑项目发布
timeout=0
cid=73

- 发布名称置空保存，检查提示信息
 - 测试结果 @编辑项目发布表单页必填提示信息正确
 - 最终测试状态 @SUCCESS
- 发布名称重复时提示信息正确
 - 测试结果 @发布名称重复时提示信息正确
 - 最终测试状态 @SUCCESS
- 编辑发布，修改应用
 - 测试结果 @编辑项目发布成功
 - 最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为已发布、计划日期、发布日期
 - 测试结果 @编辑项目发布成功
 - 最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为停止维护
 - 测试结果 @编辑项目发布成功
 - 最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为停止维护
 - 测试结果 @编辑项目发布成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/editprojectrelease.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$system = zenData('system');
$system->id->range('1,2');
$system->product->range('1');
$system->name->range('应用AAA, 应用BBB');
$system->status->range('active');
$system->integrated->range('0');
$system->createdBy->range('admin');
$system->gen(2);

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
$release->id->range('1,2');
$release->project->range('1');
$release->product->range('1');
$release->branch->range('0');
$release->name->range('release1', 'release2');
$release->system->range('1');
$release->status->range('wait');
$release->stories->range('[]');
$release->bugs->range('[]');
$release->desc->range('描述111', '描述222');
$release->deleted->range('0');
$release->gen(2);

$tester = new editProjectReleaseTester();
$tester->login();

//设置编辑项目发布的数据
$release = array(
    array('name' => ''),
    array('name' => 'release1'),
    array('systemname' => '应用BBB'),
    array('name' => 'edit-release-wait' . time(), 'status' => '未开始', 'plandate' => date('Y-m-d', strtotime('+5 day'))),
    array('name' => 'edit-release-normal' . time(), 'status' => '已发布', 'plandate' => date('Y-m-d', strtotime('+10 day')), 'releasedate' => date('Y-m-d', strtotime('+1 month'))),
    array('name' => 'edit-release-terminate' . time(), 'status' => '停止维护', 'plandate' => date('Y-m-d', strtotime('+1 month')), 'releasedate' => date('Y-m-d', strtotime('+5 days'))),
);

r($tester->editRelease($release['0'])) && p('message,status') && e('编辑项目发布表单页必填提示信息正确,SUCCESS'); // 发布名称置空保存，检查提示信息
r($tester->editRelease($release['1'])) && p('message,status') && e('发布名称重复时提示信息正确,SUCCESS');         // 发布名称重复时提示信息正确
r($tester->editRelease($release['2'])) && p('message,status') && e('编辑项目发布成功,SUCCESS');                   // 编辑发布，修改应用
r($tester->editRelease($release['3'])) && p('message,status') && e('编辑项目发布成功,SUCCESS');                   // 编辑发布，修改名称、状态改为已发布、计划日期、发布日期
r($tester->editRelease($release['4'])) && p('message,status') && e('编辑项目发布成功,SUCCESS');                   // 编辑发布，修改名称、状态改为停止维护
r($tester->editRelease($release['5'])) && p('message,status') && e('编辑项目发布成功,SUCCESS');                   // 编辑发布，修改名称、状态改为停止维护

$tester->closeBrowser();
