#!/usr/bin/env php
<?php

/**

title=测试 myZen::showWorkCount();
timeout=0
cid=17316

- 测试默认参数调用属性hasTodoCount @1
- 测试默认参数时taskCount初始化为0属性taskCount @0
- 测试默认参数时storyCount初始化为0属性storyCount @0
- 测试默认参数时bugCount初始化为0属性bugCount @0
- 测试默认参数时caseCount初始化为0属性caseCount @0
- 测试recTotal为100时属性hasTodoCount @1
- 测试recPerPage为50时属性hasTodoCount @1
- 测试pageID为2时属性hasTodoCount @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('task')->gen(0);
zenData('story')->gen(0);
zenData('bug')->gen(0);
zenData('case')->gen(0);
zenData('testtask')->gen(0);
zenData('user')->gen(10);
zenData('product')->gen(5);
zenData('project')->gen(5);

su('admin');

$myTest = new myZenTest();

r($myTest->showWorkCountTest(0, 20, 1)) && p('hasTodoCount') && e('1'); // 测试默认参数调用
r($myTest->showWorkCountTest(0, 20, 1)) && p('taskCount') && e('0'); // 测试默认参数时taskCount初始化为0
r($myTest->showWorkCountTest(0, 20, 1)) && p('storyCount') && e('0'); // 测试默认参数时storyCount初始化为0
r($myTest->showWorkCountTest(0, 20, 1)) && p('bugCount') && e('0'); // 测试默认参数时bugCount初始化为0
r($myTest->showWorkCountTest(0, 20, 1)) && p('caseCount') && e('0'); // 测试默认参数时caseCount初始化为0
r($myTest->showWorkCountTest(100, 20, 1)) && p('hasTodoCount') && e('1'); // 测试recTotal为100时
r($myTest->showWorkCountTest(0, 50, 1)) && p('hasTodoCount') && e('1'); // 测试recPerPage为50时
r($myTest->showWorkCountTest(0, 20, 2)) && p('hasTodoCount') && e('1'); // 测试pageID为2时