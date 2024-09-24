#!/usr/bin/env php
<?php

/**

title=批量创建设计测试
timeout=0
cid=4

- 批量创建概要设计类型的设计最终测试状态 @SUCCESS
- 批量创建详细设计类型的设计最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/batchcreatedesign.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
zendata('design')->loadYaml('design', false, 2)->gen(0);
$tester = new batchCreateDesignTester();
$tester->login();

$design = array(
    array('type' => '概要设计', 'name' => '概要设计01'),
    array('type' => '详细设计', 'name' => '详细设计01'),
);

r($tester->batchCreateDesign($design['0'])) && p('status') && e('SUCCESS'); //批量创建概要设计类型的设计
r($tester->batchCreateDesign($design['1'])) && p('status') && e('SUCCESS'); //批量创建详细设计类型的设计

$tester->closeBrowser();
