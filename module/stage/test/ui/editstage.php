#!/usr/bin/env php
<?php

/**

title=编辑阶段测试
timeout=0
cid=2

- 瀑布模型下校验阶段名称不能为空
 - 测试结果 @编辑阶段表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 瀑布模型下工作量占比累计不能超出100%
 - 测试结果 @工作量占比累计超出100%时提示信息正确
 - 最终测试状态 @ SUCCESS
- 瀑布模型下编辑需求类型阶段
 - 测试结果 @编辑阶段成功
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下校验阶段名称不能为空
 - 测试结果 @编辑阶段表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下工作量占比累计不能超出100%
 - 测试结果 @工作量占比累计超出100%时提示信息正确
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下编辑设计类型阶段
 - 测试结果 @编辑阶段成功
 - 最终测试状态 @ SUCCESS

*/
chdir(__DIR__);
include '../lib/editstage.ui.class.php';

$stage = zendata('stage');
$stage->id->range('1-12');
$stage->name->range('需求, 设计, 开发, 测试, 发布, 总结评审');
$stage->percent->range('10,10,40,15,10,5');
$stage->type->range('request,design,dev,qa,release,review');
$stage->projectType->range('waterfall{6},waterfallplus{6}');
$stage->createdBy->range('admin');
$stage->createdDate->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
