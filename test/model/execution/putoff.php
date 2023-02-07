#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-6');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3,迭代4');
$execution->type->range('project{2},sprint,stage,kanban,sprint');
$execution->status->range('doing{5},wait');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{4}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`,`1,6`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);
su('admin');

/**

title=测试executionModel->putoffTest();
cid=1
pid=1

wait执行延期 >> days,,5
敏捷执行延期 >> status,doing,wait
瀑布阶段延期 >> status,doing,wait
看板执行延期 >> status,doing,wait

*/

$executionIDList = array('6', '3', '4', '5');

$execution = new executionTest();
r($execution->putoffTest($executionIDList[0])) && p('0:field,old,new') && e('days,,5');           // wait执行延期
r($execution->putoffTest($executionIDList[1])) && p('0:field,old,new') && e('status,doing,wait'); // 敏捷执行延期
r($execution->putoffTest($executionIDList[2])) && p('0:field,old,new') && e('status,doing,wait'); // 瀑布阶段延期
r($execution->putoffTest($executionIDList[3])) && p('0:field,old,new') && e('status,doing,wait'); // 看板执行延期
