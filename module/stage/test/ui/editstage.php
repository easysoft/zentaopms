#!/usr/bin/env php
<?php

/**

title=编辑阶段测试
timeout=0
cid=2

- 瀑布模型下校验阶段名称不能为空
 - 测试结果 @编辑阶段表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 瀑布模型下编辑需求类型阶段
 - 测试结果 @编辑阶段成功
 - 最终测试状态 @ SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/editstage.ui.class.php';

$stage = zendata('stage');
$stage->id->range('1-6');
$stage->name->range('需求, 设计, 开发, 测试, 发布, 总结评审');
$stage->percent->range('10,10,40,15,10,5');
$stage->type->range('request,design,dev,qa,release,review');
$stage->projectType->range('waterfall{6}');
$stage->createdBy->range('admin');
$stage->createdDate->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$stage->deleted->range('0');
$stage->gen(6);

$tester = new editStageTester();
$tester->login();

$stage = array(
    array('name' => ''),
    array('name' => '瀑布需求阶段', 'type' => '需求'),
    );

r($tester->editstage($stage['0'])) && p('message,status') && e('编辑阶段表单页提示信息正确, SUCCESS'); //瀑布模型下校验阶段名称不能为空
r($tester->editstage($stage['1'])) && p('message,status') && e('编辑阶段成功, SUCCESS');               //瀑布模型下编辑需求类型阶段

$tester->closeBrowser();
