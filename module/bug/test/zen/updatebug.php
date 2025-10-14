#!/usr/bin/env php
<?php

/**

title=测试 bugZen::updateBug();
timeout=0
cid=0

- 执行bugTest模块的updateBugTest方法，参数是$bug1, array 属性title @新标题
- 执行bugTest模块的updateBugTest方法，参数是$bug2, array 
 - 属性title @更新标题
 - 属性pri @1
- 执行bugTest模块的updateBugTest方法，参数是$bug3, array 属性title @原始标题
- 执行bugTest模块的updateBugTest方法，参数是$bug4, array 属性customField @test
- 执行bugTest模块的updateBugTest方法，参数是$bug5, array 
 - 属性severity @1
 - 属性pri @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

su('admin');

$bugTest = new bugTest();

// 创建一个基础bug对象用于测试
$baseBug = new stdClass();
$baseBug->id = 1;
$baseBug->title = '原始标题';
$baseBug->pri = 2;
$baseBug->severity = 3;
$baseBug->status = 'active';

// 为每个测试创建独立的bug对象副本
$bug1 = clone $baseBug;
r($bugTest->updateBugTest($bug1, array('title' => '新标题'))) && p('title') && e('新标题');

$bug2 = clone $baseBug;
r($bugTest->updateBugTest($bug2, array('title' => '更新标题', 'pri' => 1))) && p('title,pri') && e('更新标题,1');

$bug3 = clone $baseBug;
r($bugTest->updateBugTest($bug3, array())) && p('title') && e('原始标题');

$bug4 = clone $baseBug;
r($bugTest->updateBugTest($bug4, array('customField' => 'test'))) && p('customField') && e('test');

$bug5 = clone $baseBug;
r($bugTest->updateBugTest($bug5, array('severity' => 1, 'pri' => 3))) && p('severity,pri') && e('1,3');