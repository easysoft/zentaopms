#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/editdesign.ui.class.php';

zendata('design')->loadYaml('design', false, 2)->gen(2);
zendata('designspec')->gen(0);
$tester = new editDesignTester();
$tester->login();

$design = array(
    array('type' => '', 'name' => '设计1'),
    array('type' => '设计1', 'name' => ''),
    array('product' => '所有产品', 'type' => '概要设计', 'name' => '编辑设计1'),
);

r($tester->editDesign($design['0'])) && p('message,status') && e('编辑设计表单页提示信息正确, success'); //校验设计类型不能为空
r($tester->editDesign($design['1'])) && p('message,status') && e('编辑设计表单页提示信息正确, success'); //校验设计名称不能为空
r($tester->editDesign($design['2'])) && p('status') && e('success');                                     //编辑设计名称

$tester->closeBrowser();
