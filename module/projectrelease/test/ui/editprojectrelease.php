#!/usr/bin/env php
<?php

/**

title=编辑项目发布
timeout=0
cid=73

- 发布名称置空保存，检查提示信息测试结果 @编辑项目发布表单页提示信息正确
- 编辑发布，修改名称、状态改为未开始、计划日期最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为已发布、计划日期、发布日期最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为停止维护最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editprojectrelease.ui.class.php';

zendata('story')->loadYaml('story', false, 1)->gen(5);
zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('product')->loadYaml('product', false, 1)->gen(1);
zendata('storyspec')->loadYaml('storyspec', false, 1)->gen(5);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);

$tester = new editProjectReleaseTester();
$tester->login();

//设置编辑项目发布的数据
$release = array(
    array('name' => ''),
    array('name' => '编辑项目发布1'.time(), 'status' => '未开始', 'plandate' => date('Y-m-d', strtotime('+5 day'))),
    array('name' => '编辑项目发布2'.time(), 'status' => '已发布', 'plandate' => date('Y-m-d', strtotime('+10 day')), 'releasedate' => date('Y-m-d', strtotime('+1 month'))),
    array('name' => '编辑项目发布3'.time(), 'status' => '停止维护', 'plandate' => date('Y-m-d', strtotime('+1 month')), 'releasedate' => date('Y-m-d', strtotime('+5 days'))),
);

r($tester->checkInput($release['0'])) && p('message') && e('编辑项目发布表单页提示信息正确');   // 发布名称置空保存，检查提示信息
r($tester->checkInput($release['1'])) && p('status')  && e('SUCCESS');                          // 编辑发布，修改名称、状态改为未开始、计划日期
r($tester->checkInput($release['2'])) && p('status')  && e('SUCCESS');                          // 编辑发布，修改名称、状态改为已发布、计划日期、发布日期
r($tester->checkInput($release['3'])) && p('status')  && e('SUCCESS');                          // 编辑发布，修改名称、状态改为停止维护

$tester->closeBrowser();
