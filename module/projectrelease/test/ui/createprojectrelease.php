#!/usr/bin/env php
<?php

/**

title=创建项目发布
timeout=0
cid=73

- 创建项目发布时检查必填校验测试结果 @创建项目发布表单页必填提示信息正确
- 创建一个已发布的发布，且选择已有应用测试结果 @创建项目发布成功
- 创建一个未开始的发布，且勾选新建应用测试结果 @创建项目发布成功

*/
chdir(__DIR__);
include '../lib/createprojectrelease.ui.class.php';

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
    array('name' => '一个已发布的项目发布'.time(), 'status' => '已发布', 'plandate' => date('Y-m-d', strtotime('+1 day')), 'releasedate' => date('Y-m-d', strtotime('+1 month'))),
    array('systemname' => '新建应用1', 'name' => '一个未开始的项目发布'.time(), 'status' => '未开始', 'plandate' => date('Y-m-d', strtotime('+1 day'))),
