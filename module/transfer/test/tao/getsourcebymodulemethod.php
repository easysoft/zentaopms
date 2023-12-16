#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
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

title=测试 transfer->mergeConfig();
timeout=0
cid=1

- 测试获取合并配置后的时间字段属性9 @estStarted
- 测试传入模块为空时的时间字段 @assignedDate

*/
$transfer = new transferTest();
$_SESSION['taskTransferParams']['executionID'] = 1;

$moduleList = array('task', 'story', ''); // 当前模块
$callModule = array('tree', 'project', 'execution', '');  // 调用模块
$methodList = array('getPairs', 'getTaskOptionMenu', 'getByIdList', ''); // 被调用方法
$paramsList = array('', '$executionID&execution', '$executionID', array('executionIdList'=> array(3, 4))); // 被调用方法的参数列表
$pairsList  = array('', array('id', 'name'));  // 关联数组键值对

r($transfer->getSourceByModuleMethodTest($moduleList[2], $callModule[0], $methodList[0])) && p('')  && e('Module is empty');   // module为空时
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[3], $methodList[0])) && p('')  && e('Call module is empty');  // 调用模块为空时
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[0], $methodList[3])) && p('')  && e('Method is empty');  // 调用方法为空时
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[1], $methodList[0])) && p('2') && e('项目1');           // 调用project:getPairs方法
r($transfer->getSourceByModuleMethodTest($moduleList[0], $callModule[2], $methodList[2], $paramsList[3], $pairsList[1])) && p('3') && e('迭代1'); // 调用execution:getByIdList方法并将结果生成id=>name 形式的关联数组
