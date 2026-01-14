#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->model->range('{1},scrum,{3}');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试 transfer->getSourceByModuleMethod();
timeout=0
cid=19335

- module为空时 @Module is empty
- 调用模块为空时 @Call module is empty
- 调用方法为空时 @Method is empty
- 调用project:getPairs方法属性2 @项目1
- 调用execution:getByIdList方法并将结果生成id=>name 形式的关联数组属性3 @迭代1

*/
$transfer = new transferTaoTest();
$_SESSION['taskTransferParams']['executionID']  = 1;
$_SESSION['taskTransferParams']['ignoreVision'] = false;

$moduleList = array('task', 'story', ''); // 当前模块
$callModule = array('tree', 'project', 'execution', '');  // 调用模块
$methodList = array('getPairs', 'getTaskOptionMenu', 'getByIdList', ''); // 被调用方法
$paramsList = array('', '$executionID&execution', '$executionID', array('executionIdList'=> array(3, 4)), '$ignoreVision'); // 被调用方法的参数列表
$pairsList  = array('', array('id', 'name'));  // 关联数组键值对

r($transfer->getSourceByModuleMethodTest($moduleList[2], $callModule[0], $methodList[0])) && p('')  && e('Module is empty');   // module为空时
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[3], $methodList[0])) && p('')  && e('Call module is empty');  // 调用模块为空时
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[0], $methodList[3])) && p('')  && e('Method is empty');  // 调用方法为空时
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[1], $methodList[0], $paramsList[4])) && p('2') && e('项目1');           // 调用project:getPairs方法
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[2], $methodList[2], $paramsList[3], $pairsList[1])) && p('3') && e('迭代1'); // 调用execution:getByIdList方法并将结果生成id=>name 形式的关联数组