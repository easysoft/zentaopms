#!/usr/bin/env php
<?php

/**

title=创建项目发布
timeout=0
cid=73

- 缺少发布名称，检查提示信息测试结果 @创建项目发布表单页提示信息正确
- 创建状态为未开始的发布最终测试状态 @SUCCESS
- 创建状态为已发布的发布最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createprojectrelease.ui.class.php';

$tester = new createProjectReleaseTester();
$tester->login();

//设置项目发布数据
$release = array(
    array('name' => ''),
    array('name' => '一个未开始的项目发布'.time(), 'status' => '未开始', 'plandate' => date('Y-m-d', strtotime('+1 day'))),
    array('name' => '一个已发布的项目发布'.time(), 'status' => '已发布', 'plandate' => date('Y-m-d', strtotime('+1 day')), 'releasedate' => date('Y-m-d', strtotime('+1 month'))),
);

r($tester->checkInput($release['0'],$project)) && p('message') && e('创建项目发布表单页提示信息正确');   // 缺少发布名称，检查提示信息
r($tester->checkInput($release['1'],$project)) && p('status')  && e('SUCCESS');                          // 创建状态为未开始的发布
r($tester->checkInput($release['2'],$project)) && p('status')  && e('SUCCESS');                          // 创建状态为已发布的发布

$tester->closeBrowser();
