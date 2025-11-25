#!/usr/bin/env php
<?php

/**

title=编辑设计测试
timeout=0
cid=2

- 校验设计名称不能为空
 - 测试结果 @编辑设计表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 编辑设计名称最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/editdesign.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
zendata('design')->loadYaml('design', false, 2)->gen(2);
zendata('designspec')->loadYaml('designspec', false, 2)->gen(2);
$tester = new editDesignTester();
$tester->login();

$design = array(
    array('type' => '设计1', 'name' => ''),
    array('product' => '所有产品', 'type' => '概要设计', 'name' => '编辑设计1'),
);

r($tester->editDesign($design['0'])) && p('message,status') && e('设计名称必填提示信息正确, SUCCESS'); //校验设计名称不能为空
r($tester->editDesign($design['1'])) && p('status') && e('SUCCESS');                                     //编辑设计名称

$tester->closeBrowser();
