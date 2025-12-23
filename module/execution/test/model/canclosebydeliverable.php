#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->project->range('1-5');
$execution->name->range('迭代1,迭代2,迭代3,迭代4,迭代5');
$execution->type->range('stage');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,1');
$execution->grade->range('1{2},2{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`1,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);
su('admin');

/**

title=executionModel->getByID();
timeout=0
cid=16276

- 查看执行1是否可关闭 @1
- 查看执行2是否可关闭 @1
- 查看执行3是否可关闭 @1
- 查看执行4是否可关闭 @1
- 查看执行5是否可关闭 @1

*/

global $tester;
$tester->loadModel('execution');
$execution1 = $tester->execution->getByID(1);
$execution2 = $tester->execution->getByID(2);
$execution3 = $tester->execution->getByID(3);
$execution4 = $tester->execution->getByID(4);
$execution5 = $tester->execution->getByID(5);

r($tester->execution->canCloseByDeliverable($execution1)) && p('') && e('1'); // 查看执行1是否可关闭
r($tester->execution->canCloseByDeliverable($execution2)) && p('') && e('1'); // 查看执行2是否可关闭
r($tester->execution->canCloseByDeliverable($execution3)) && p('') && e('1'); // 查看执行3是否可关闭
r($tester->execution->canCloseByDeliverable($execution4)) && p('') && e('1'); // 查看执行4是否可关闭
r($tester->execution->canCloseByDeliverable($execution5)) && p('') && e('1'); // 查看执行5是否可关闭