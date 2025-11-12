#!/usr/bin/env php
<?php

/**

title=测试 myZen::showWorkCountNotInOpen();
timeout=0
cid=0

- 查看各个模块的数量属性feedback @0
- 查看各个模块的数量属性feedback @0
- 查看各个模块的数量属性feedback @0
- 查看各个模块的数量属性feedback @0
- 查看各个模块的数量属性feedback @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/myzen.unittest.class.php';

// 用户登录
su('admin');

zenData('feedback')->gen(0);

// 创建测试实例
$myTest = new myZenTest();

$count = array ('task' => 0, 'story' => 0, 'bug' => 0, 'case' => 0, 'testtask' => 0, 'requirement' => 0, 'epic' => 0, 'issue' => 0, 'risk' => 0, 'reviewissue' => 0, 'qa' => 0, 'meeting' => 0, 'ticket' => 0, 'feedback' => 0);

r($myTest->showWorkCountNotInOpenTest($count, 0, 10, 1))  && p('feedback') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest($count, 10, 20, 1)) && p('feedback') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest($count, 15, 30, 1)) && p('feedback') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest($count, 20, 40, 1)) && p('feedback') && e('0'); // 查看各个模块的数量
r($myTest->showWorkCountNotInOpenTest($count, 25, 50, 1)) && p('feedback') && e('0'); // 查看各个模块的数量