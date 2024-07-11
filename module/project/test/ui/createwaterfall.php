#!/usr/bin/env php
<?php

/**

title=创建瀑布项目测试
timeout=0
cid=73

- 缺少项目名称，创建失败
 -  测试结果 @创建瀑布项目表单页提示信息正确
 -  最终测试状态 @SUCCESS
- 使用默认选项创建瀑布项目 最终测试状态 @SUCCESS
- 创建重复名称的瀑布项目， 测试结果 @创建瀑布项目表单页提示信息正确
- 创建项目型后的跳转链接检查
 - 属性module @programplan
 - 属性method @create
- 创建正常项目成功 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createwaterfall.ui.class.php';

zendata('projet')->loadYaml('project', false, 2)->gen(10);
$tester = new createWaterfallTester();
$tester->login();

$waterfall = array(
    array('name' => '', 'PM' => 'admin'),
    array('name' => '默认瀑布项目'.time()),
    array('name' => '项目型瀑布项目'.time(), 'end' => date('Y-m-d', strtotime('+30 days')), 'PM' => 'admin'),
);

r($tester->createdefault($waterfall['0'])) && p('message,status') && e('创建瀑布项目成功，success');                // 缺少项目名称，创建失败
r($tester->createDefault($waterfall['1'])) && p('status')         && e('SUCCESS');                                  // 使用默认选项创建瀑布项目
r($tester->createDefault($waterfall['1'])) && p('message')        && e('创建瀑布项目表单页提示信息正确');           // 创建重复项目名称
r($tester->checkLocating($waterfall['2'])) && p('module,method')  && e('programplan','create');                         // 创建项目型瀑布项目后的跳转

$tester->closeBrowser();
