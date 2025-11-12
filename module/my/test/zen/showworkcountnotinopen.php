#!/usr/bin/env php
<?php

/**

title=测试 myZen::showWorkCountNotInOpen();
timeout=0
cid=0

- 执行myTest模块的showWorkCountNotInOpenTest方法，参数是array
 - 属性feedback @0
 - 属性ticket @0
- 执行myTest模块的showWorkCountNotInOpenTest方法，参数是array
 - 属性feedback @0
 - 属性ticket @0
- 执行myTest模块的showWorkCountNotInOpenTest方法，参数是array
 - 属性issue @0
 - 属性risk @0
 - 属性qa @3
 - 属性meeting @0
- 执行myTest模块的showWorkCountNotInOpenTest方法，参数是array 属性demand @0
- 执行myTest模块的showWorkCountNotInOpenTest方法，参数是array 属性qa @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/myzen.unittest.class.php';

// 用户登录
su('admin');

zenData('feedback')->gen(0);
zenData('auditplan')->gen(0);
zenData('ticket')->gen(0);

// 创建测试实例
$myTest = new myZenTest();

r($myTest->showWorkCountNotInOpenTest(0, 10, 1))  && p('') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest(10, 20, 1)) && p('') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest(15, 30, 1)) && p('') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest(20, 40, 1)) && p('') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest(25, 50, 1)) && p('') && e('0'); // 查看各个模块的数量