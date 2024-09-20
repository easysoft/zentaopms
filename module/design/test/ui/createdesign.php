#!/usr/bin/env php
<?php

/**

title=创建设计测试
timeout=0
cid=1

- 校验设计类型不能为空
 - 测试结果 @创建设计表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 校验设计名称不能为空
 - 测试结果 @创建设计表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建概要设计类型的设计 最终测试状态 @SUCCESS
- 创建详细设计类型的设计 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createdesign.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
$tester = new createDesignTester();
$tester->login();

$design = array(
    array('type' => '', 'name' => '概要设计1'),
    array('type' => '概要设计', 'name' => ''),
    array('product' => '所有产品', 'type' => '概要设计', 'name' => '概要设计1'),
    array('product' => '所有产品', 'type' => '详细设计', 'name' => '详细设计1'),
);

r($tester->createDesign($design['0'])) && p('message,status') && e('创建设计表单页提示信息正确, SUCCESS'); //校验设计类型不能为空
r($tester->createDesign($design['1'])) && p('message,status') && e('创建设计表单页提示信息正确, SUCCESS'); //校验设计名称不能为空
r($tester->createDesign($design['2'])) && p('status') && e('SUCCESS');                                     //创建概要设计类型的设计
r($tester->createDesign($design['3'])) && p('status') && e('SUCCESS');                                     //创建详细设计类型的设计

$tester->closeBrowser();
