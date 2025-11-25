#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=executionModel->getBeginEnd4CFD();
timeout=0
cid=0

- ID 1 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
 -  @1
 - 属性1 @1
- ID 2 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
 -  @1
 - 属性1 @1
- ID 3 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
 -  @1
 - 属性1 @1
- ID 4 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
 -  @1
 - 属性1 @1
- ID 5 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
 -  @1
 - 属性1 @1

*/

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('1-5')->prefix('看板');
$execution->type->range('kanban');
$execution->status->range('wait{3},suspended,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$executionIDList = array(1, 2, 3, 4, 5);

$executionTester = new executionTest();
r($executionTester->getBeginEnd4CFDTest($executionIDList[0])) && p('0,1') && e('1,1'); // ID 1 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
r($executionTester->getBeginEnd4CFDTest($executionIDList[1])) && p('0,1') && e('1,1'); // ID 2 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
r($executionTester->getBeginEnd4CFDTest($executionIDList[2])) && p('0,1') && e('1,1'); // ID 3 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
r($executionTester->getBeginEnd4CFDTest($executionIDList[3])) && p('0,1') && e('1,1'); // ID 4 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
r($executionTester->getBeginEnd4CFDTest($executionIDList[4])) && p('0,1') && e('1,1'); // ID 5 的专业研发看板累计流图默认开始和结束时间是不是14天前和今天一致
