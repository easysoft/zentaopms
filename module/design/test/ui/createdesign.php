#!/usr/bin/env php
<?php
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

r($tester->createDesign($design['0'])) && p('message,status') && e('创建设计表单页提示信息正确, success'); //校验设计类型不能为空
r($tester->createDesign($design['1'])) && p('message,status') && e('创建设计表单页提示信息正确, success'); //校验设计名称不能为空
r($tester->createDesign($design['2'])) && p('status') && e('success');                                     //创建概要设计类型的设计
r($tester->createDesign($design['3'])) && p('status') && e('success');                                     //创建详细设计类型的设计

$tester->closeBrowser();
