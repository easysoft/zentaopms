#!/usr/bin/env php
<?php

/**

title=批量创建阶段测试
timeout=0
cid=3

- 瀑布模型下校验工作量占比不能为空
 - 测试结果 @工作量占比必填提示信息正确
 - 最终测试状态 @ SUCCESS
- 瀑布模型下校验工作量占非数字输入
 - 测试结果 @工作量占比必填提示信息正确
 - 最终测试状态 @ SUCCESS
- 瀑布模型下校验工作量占比累计不能超过100%
 - 测试结果 @工作量占比累计超出100%时提示信息正确
 - 最终测试状态 @ SUCCESS
- 瀑布模型下批量新建需求类型阶段
 - 测试结果 @批量新建阶段成功
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下校验工作量占比不能为空
 - 测试结果 @工作量占比必填提示信息正确
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下校验工作量占非数字输入
 - 测试结果 @工作量占比必填提示信息正确
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下校验工作量占比累计不能超过100%
 - 测试结果 @工作量占比累计超出100%时提示信息正确
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下批量新建需求类型阶段
 - 测试结果 @批量新建阶段成功
 - 最终测试状态 @ SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/batchcreatestage.ui.class.php';

$stage = zendata('stage');
$stage->id->range('1-12');
$stage->name->range('需求, 设计, 开发, 测试, 发布, 总结评审');
$stage->percent->range('10,10,40,15,10,5');
$stage->type->range('request,design,dev,qa,release,review');
$stage->projectType->range('waterfall{6},waterfallplus{6}');
$stage->createdBy->range('admin');
$stage->createdDate->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$stage->deleted->range('0');
$stage->gen(12);

$tester = new batchCreateStageTester();
$tester->login();

$stage = array(
    array('name' => '瀑布需求阶段', 'type' => '需求'),
    array('name' => '融合瀑布需求阶段', 'type' => '需求'),
);

r($tester->batchCreateStage($stage['0'], 'waterfall'))     && p('message,status') && e('批量新建阶段成功, SUCCESS'); //瀑布模型下批量新建需求类型阶段
r($tester->batchCreateStage($stage['1'], 'waterfallplus')) && p('message,status') && e('批量新建阶段成功, SUCCESS'); //融合瀑布模型下批量新建需求类型阶段

$tester->closeBrowser();
