#!/usr/bin/env php
<?php
/**

title=测试 customModel->mergeFeatureBar();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('lang')->config('lang')->gen(10);
zdTable('userquery')->gen(0);
zdTable('user')->gen(5);
su('admin');

$modules = array('my', 'product', 'project', 'execution');
$methods = array('work', 'browse', 'browse', 'task');

$customTester  = new customTest();
r($customTester->mergeFeatureBarTest($modules[0], $methods[0])) && p()      && e('0');    // 获取地盘-待处理-任务列表筛选标签
r($customTester->mergeFeatureBarTest($modules[1], $methods[1])) && p()      && e('0');    // 获取产品-需求列表筛选标签
r($customTester->mergeFeatureBarTest($modules[2], $methods[2])) && p('all') && e('全部'); // 获取项目-项目列表筛选标签
r($customTester->mergeFeatureBarTest($modules[3], $methods[3])) && p()      && e('0');    // 获取执行-任务列表筛选标签

zdTable('lang')->gen(0);
