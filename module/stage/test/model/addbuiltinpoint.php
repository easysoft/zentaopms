#!/usr/bin/env php
<?php
/**

title=测试 stageModel->addBuiltinPoint();
cid=1

- 获取TR1评审点
 - 第1条的title属性 @TR1
 - 第1条的type属性 @TR
 - 第1条的category属性 @TR1
- 获取CDCP评审点
 - 第2条的title属性 @CDCP
 - 第2条的type属性 @DCP
 - 第2条的category属性 @CDCP
- 获取TR2评审点
 - 第3条的title属性 @TR2
 - 第3条的type属性 @TR
 - 第3条的category属性 @TR2
- 获取TR3评审点
 - 第4条的title属性 @TR3
 - 第4条的type属性 @TR
 - 第4条的category属性 @TR3
- 获取PDCP评审点
 - 第5条的title属性 @PDCP
 - 第5条的type属性 @DCP
 - 第5条的category属性 @PDCP
- 获取TR4评审点
 - 第6条的title属性 @TR4
 - 第6条的type属性 @TR
 - 第6条的category属性 @TR4
- 获取TR4A评审点
 - 第7条的title属性 @TR4A
 - 第7条的type属性 @TR
 - 第7条的category属性 @TR4A
- 获取TR5评审点
 - 第8条的title属性 @TR5
 - 第8条的type属性 @TR
 - 第8条的category属性 @TR5
- 获取TR6评审点
 - 第9条的title属性 @TR6
 - 第9条的type属性 @TR
 - 第9条的category属性 @TR6
- 获取ADCP评审点
 - 第10条的title属性 @ADCP
 - 第10条的type属性 @DCP
 - 第10条的category属性 @ADCP

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('decision')->gen(0);

$workflowGroup = zenData('workflowgroup');
$workflowGroup->id->range('1-5');
$workflowGroup->type->range('project');
$workflowGroup->projectModel->range('ipd');
$workflowGroup->projectType->range('ipd,tpd,cbb,cpdproduct,cpdproject');
$workflowGroup->name->range('IPD集成产品研发,IPD预研产品研发,IPD平台产品研发,IPD定制产品研发,IPD定制项目研发');
$workflowGroup->code->range('ipdproduct,tpdproduct,cbbproduct,cpdproduct,cpdproject');
$workflowGroup->status->range('normal');
$workflowGroup->main->range('1');
$workflowGroup->gen(5);

global $tester;
$stageTester = $tester->loadModel('stage');
$stageTester->addBuiltinPoint(1);
$IPDpoint = $stageTester->dao->select('*')->from(TABLE_DECISION)->where('workflowGroup')->eq(1)->fetchAll('id');
r($IPDpoint) && p('1:title,type,category')  && e('TR1,TR,TR1');    //获取TR1评审点
r($IPDpoint) && p('2:title,type,category')  && e('CDCP,DCP,CDCP'); //获取CDCP评审点
r($IPDpoint) && p('3:title,type,category')  && e('TR2,TR,TR2');    //获取TR2评审点
r($IPDpoint) && p('4:title,type,category')  && e('TR3,TR,TR3');    //获取TR3评审点
r($IPDpoint) && p('5:title,type,category')  && e('PDCP,DCP,PDCP'); //获取PDCP评审点
r($IPDpoint) && p('6:title,type,category')  && e('TR4,TR,TR4');    //获取TR4评审点
r($IPDpoint) && p('7:title,type,category')  && e('TR4A,TR,TR4A');  //获取TR4A评审点
r($IPDpoint) && p('8:title,type,category')  && e('TR5,TR,TR5');    //获取TR5评审点
r($IPDpoint) && p('9:title,type,category')  && e('TR6,TR,TR6');    //获取TR6评审点
r($IPDpoint) && p('10:title,type,category') && e('ADCP,DCP,ADCP'); //获取ADCP评审点
