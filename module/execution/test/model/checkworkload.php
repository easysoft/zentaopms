#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-7');
$execution->name->range('项目集1,项目1,阶段1,阶段2,阶段3,子阶段1,子阶段2');
$execution->type->range('program,project,stage{5}');
$execution->parent->range('0,1,0{3},3{2}');
$execution->project->range('0{2},2{5}');
$execution->percent->range('0{2},30{3},10{2}');
$execution->status->range('wait');
$execution->grade->range('1,2,1{3},2{2}');
$execution->model->range('[],waterfall,[]{5}');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(7);

zenData('product')->loadYaml('product')->gen(7);

$projectProduct = zenData('projectproduct');
$projectProduct->product->range('1');
$projectProduct->project->range('1-7');
$projectProduct->gen(7);

/**

title=测试executionModel->checkWorkload();
timeout=0
cid=16284

*/

$executionIDList = array(3, 6);
$typeList        = array('create', 'update');
$percentList     = array(-1, 10, 200);

$executionTester = new executionModelTest();
r($executionTester->checkWorkloadTest($executionIDList[0], $typeList[0], $percentList[0])) && p('percent', ';') && e('"工作量占比"必须为数字');                                     // 检查创建执行时，填写空工作量判断
r($executionTester->checkWorkloadTest($executionIDList[0], $typeList[0], $percentList[1])) && p()               && e('1');                                                          // 检查创建执行时，填写正确的工作量判断
r($executionTester->checkWorkloadTest($executionIDList[0], $typeList[0], $percentList[2])) && p('percent', ';') && e('工作量占比累计不应当超过100%, 当前产品下的工作量之和为0%');   // 检查创建执行时，填写错误的工作量判断
r($executionTester->checkWorkloadTest($executionIDList[1], $typeList[1], $percentList[0])) && p('percent', ';') && e('"工作量占比"必须为数字');                                     // 检查编辑子阶段时，填写空工作量判断
r($executionTester->checkWorkloadTest($executionIDList[1], $typeList[1], $percentList[1])) && p()               && e('1');                                                          // 检查编辑子阶段时，填写正确的工作量判断
r($executionTester->checkWorkloadTest($executionIDList[1], $typeList[1], $percentList[2])) && p('percent', ';') && e('工作量占比累计不应当超过100%, 当前产品下的工作量之和为210%'); // 检查编辑子阶段时，填写错误的工作量判断
