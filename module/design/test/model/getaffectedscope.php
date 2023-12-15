#!/usr/bin/env php
<?php
/**

title=测试 designModel->getAffectedScope();
cid=1

- 测试设计ID为0时，受影响的任务信息 @0
- 测试设计ID为1时，受影响的任务信息
 - 第1条的id属性 @4
 - 第1条的name属性 @关联设计的任务4
 - 第1条的design属性 @1
- 测试设计ID为2时，受影响的任务信息 @0
- 测试设计ID不存在时，受影响的任务信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('design')->config('design')->gen(2);
zdTable('task')->config('task')->gen(5);

$idList = array(0, 1, 2, 3);

$designTester = new designTest();
r($designTester->getAffectedScopeTest($idList[0])) && p()                   && e('0');                   // 测试设计ID为0时，受影响的任务信息
r($designTester->getAffectedScopeTest($idList[1])) && p('1:id,name,design') && e('4,关联设计的任务4,1'); // 测试设计ID为1时，受影响的任务信息
r($designTester->getAffectedScopeTest($idList[2])) && p()                   && e('0');                   // 测试设计ID为2时，受影响的任务信息
r($designTester->getAffectedScopeTest($idList[3])) && p()                   && e('0');                   // 测试设计ID不存在时，受影响的任务信息
