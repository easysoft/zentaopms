#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-7');
$execution->name->range('项目集1,项目1,父阶段1,子阶段1,子阶段2,子阶段3,子阶段4');
$execution->type->range('program,project,stage{5}');
$execution->parent->range('0,1,0,3{4}');
$execution->status->range('wait');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(7);

/**

title=测试executionModel->getChildExecutionsTest();
timeout=0
cid=16308

- 查询子阶段1第4条的name属性 @子阶段1
- 查询子阶段2第5条的name属性 @子阶段2
- 查询子阶段3第6条的name属性 @子阶段3
- 查询子阶段4第7条的name属性 @子阶段4
- 查询子阶段数量 @4

*/

$executionID = 3;
$count       = array(0, 1);

$executionTester = new executionTest();
r($executionTester->getChildExecutionsTest($executionID, $count[0])) && p('4:name') && e('子阶段1'); // 查询子阶段1
r($executionTester->getChildExecutionsTest($executionID, $count[0])) && p('5:name') && e('子阶段2'); // 查询子阶段2
r($executionTester->getChildExecutionsTest($executionID, $count[0])) && p('6:name') && e('子阶段3'); // 查询子阶段3
r($executionTester->getChildExecutionsTest($executionID, $count[0])) && p('7:name') && e('子阶段4'); // 查询子阶段4
r($executionTester->getChildExecutionsTest($executionID, $count[1])) && p()         && e('4');       // 查询子阶段数量
