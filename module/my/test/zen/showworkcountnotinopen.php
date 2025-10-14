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
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 创建测试用的pager对象
$pager = new stdclass();
$pager->recTotal = 0;

// 用户登录
su('admin');

// 创建测试实例
$myTest = new myTest();

r($myTest->showWorkCountNotInOpenTest(array('task' => 5, 'story' => 3, 'bug' => 2, 'feedback' => 0, 'ticket' => 0), $pager, 'open', 'rnd')) && p('feedback,ticket') && e('0,0');
r($myTest->showWorkCountNotInOpenTest(array('task' => 5, 'story' => 3, 'bug' => 2, 'feedback' => 0, 'ticket' => 0), $pager, 'biz', 'rnd')) && p('feedback,ticket') && e('0,0');
r($myTest->showWorkCountNotInOpenTest(array('task' => 5, 'story' => 3, 'bug' => 2, 'issue' => 0, 'risk' => 0, 'qa' => 0, 'meeting' => 0), $pager, 'max', 'rnd')) && p('issue,risk,qa,meeting') && e('0,0,3,0');
r($myTest->showWorkCountNotInOpenTest(array('task' => 5, 'story' => 3, 'bug' => 2, 'demand' => 0), $pager, 'ipd', 'or')) && p('demand') && e('0');
r($myTest->showWorkCountNotInOpenTest(array(), $pager, 'max', 'rnd')) && p('qa') && e('3');
