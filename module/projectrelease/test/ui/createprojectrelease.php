#!/usr/bin/env php
<?php

/**

title=创建项目发布
timeout=0
cid=73

- 创建项目发布时检查必填校验测试结果 @创建项目发布表单页必填提示信息正确
- 创建一个已发布的发布，且选择已有应用测试结果 @创建项目发布成功
- 创建一个未开始的发布，且勾选新建应用测试结果 @创建项目发布成功
- 发布名称重复时提示信息校验测试结果 @发布名称重复时提示信息正确

*/
chdir(__DIR__);
include '../lib/ui/createprojectrelease.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('product')->loadYaml('product', false, 1)->gen(1);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);

$project = zenData('system');
$project->id->range('1');
$project->product->range('1');
$project->name->range('应用AAA');
$project->status->range('active');
$project->createdBy->range('admin');
$project->gen(1);

$tester = new createProjectReleaseTester();
$tester->login();

//设置项目发布数据
$release = array(
    array('name' => '第一个发布', 'status' => '已发布', 'plandate' => date('Y-m-d', strtotime('+1 day')), 'releasedate' => date('Y-m-d', strtotime('+1 month'))),
    array('systemname' => '新建应用1', 'name' => '第二个发布', 'status' => '未开始', 'plandate' => date('Y-m-d', strtotime('+1 day'))),
    array('name' => '第二个发布', 'status' => '未开始', 'plandate' => date('Y-m-d', strtotime('+1 day'))),
);

r($tester->checkRequired())                     && p('message') && e('创建项目发布表单页必填提示信息正确'); // 创建项目发布时检查必填校验
r($tester->createProjectRelease($release['0'])) && p('message') && e('创建项目发布成功');                   // 创建一个已发布的发布，且选择已有应用
r($tester->createProjectRelease($release['1'])) && p('message') && e('创建项目发布成功');                   // 创建一个未开始的发布，且勾选新建应用
r($tester->createProjectRelease($release['2'])) && p('message') && e('发布名称重复时提示信息正确');         // 发布名称重复时提示信息校验

$tester->closeBrowser();
