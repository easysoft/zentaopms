#!/usr/bin/env php
<?php

/**

title=测试 myZen::showWorkCountNotInOpen();
timeout=0
cid=17317

- 测试开源版(open)下空count数组属性isBiz @0
- 测试开源版(open)下空count数组的isMax属性isMax @0
- 测试开源版(open)下空count数组的isIPD属性isIPD @0
- 测试开源版(open)下feedback计数为0属性feedback @0
- 测试非空count数组保留原有task计数属性task @5
- 测试非空count数组保留原有story计数属性story @3
- 测试非空count数组保留原有bug计数属性bug @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('feedback')->gen(0);
zenData('ticket')->gen(0);
zenData('issue')->gen(0);
zenData('risk')->gen(0);
zenData('nc')->gen(0);
zenData('auditplan')->gen(0);
zenData('meeting')->gen(0);
zenData('demand')->gen(0);
zenData('user')->gen(10);
zenData('product')->gen(5);

su('admin');

$myTest = new myZenTest();

$emptyCount = array('task' => 0, 'story' => 0, 'bug' => 0, 'case' => 0, 'testtask' => 0, 'requirement' => 0, 'issue' => 0, 'risk' => 0, 'qa' => 0, 'meeting' => 0, 'ticket' => 0, 'feedback' => 0);
$nonEmptyCount = array('task' => 5, 'story' => 3, 'bug' => 2);

r($myTest->showWorkCountNotInOpenTest($emptyCount)) && p('isBiz') && e('0'); // 测试开源版(open)下空count数组
r($myTest->showWorkCountNotInOpenTest($emptyCount)) && p('isMax') && e('0'); // 测试开源版(open)下空count数组的isMax
r($myTest->showWorkCountNotInOpenTest($emptyCount)) && p('isIPD') && e('0'); // 测试开源版(open)下空count数组的isIPD
r($myTest->showWorkCountNotInOpenTest($emptyCount)) && p('feedback') && e('0'); // 测试开源版(open)下feedback计数为0
r($myTest->showWorkCountNotInOpenTest($nonEmptyCount)) && p('task') && e('5'); // 测试非空count数组保留原有task计数
r($myTest->showWorkCountNotInOpenTest($nonEmptyCount)) && p('story') && e('3'); // 测试非空count数组保留原有story计数
r($myTest->showWorkCountNotInOpenTest($nonEmptyCount)) && p('bug') && e('2'); // 测试非空count数组保留原有bug计数