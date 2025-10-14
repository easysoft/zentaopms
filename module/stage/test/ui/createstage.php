#!/usr/bin/env php
<?php
/**

title=创建阶段测试
timeout=0
cid=1

- 瀑布模型下校验阶段名称不能为空
 - 测试结果 @新建阶段表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 瀑布模型下新建需求类型阶段
 - 测试结果 @新建阶段成功
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下校验阶段名称不能为空
 - 测试结果 @新建阶段表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 融合瀑布模型下新建设计类型阶段
- 测试结果 @新建阶段成功

*/
chdir(__DIR__);
include '../lib/ui/createstage.ui.class.php';

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

$tester = new createStageTester();
$tester->login();

$stage = array(
    array('name' => ''),
    array('name' => '瀑布需求阶段', 'type' => '需求'),
    array('name' => ''),
    array('name' => '融合瀑布设计阶段', 'type' => '设计'),
);

r($tester->createstage($stage['0'], 'waterfall'))     && p('message,status') && e('新建阶段表单页提示信息正确, SUCCESS'); //瀑布模型下校验阶段名称不能为空
r($tester->createstage($stage['1'], 'waterfall'))     && p('message,status') && e('新建阶段成功, SUCCESS');               //瀑布模型下新建需求类型阶段
r($tester->createstage($stage['2'], 'waterfallplus')) && p('message,status') && e('新建阶段表单页提示信息正确, SUCCESS'); //融合瀑布模型下校验阶段名称不能为空
r($tester->createstage($stage['3'], 'waterfallplus')) && p('message,status') && e('新建阶段成功, SUCCESS');               //融合瀑布模型下新建设计类型阶段

$tester->closeBrowser();
