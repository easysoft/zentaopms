#!/usr/bin/env php
<?php
/**

title=测试 customModel->getFeatureMenu();
timeout=0
cid=15896

- 获取地盘-待处理-任务列表筛选标签 @0
- 获取产品-需求列表筛选标签
 - 第0条的name属性 @allstory
 - 第0条的text属性 @全部
- 获取项目-项目列表筛选标签
 - 第0条的name属性 @all
 - 第0条的text属性 @全部
- 获取执行-任务列表筛选标签
 - 第0条的name属性 @all
 - 第0条的text属性 @全部

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('user')->gen(5);
su('admin');

$modules = array('my', 'product', 'project', 'execution');
$methods = array('work', 'browse', 'browse', 'task');

$customTester  = new customTest();
r($customTester->getFeatureMenuTest($modules[0], $methods[0])) && p('')            && e('0');             // 获取地盘-待处理-任务列表筛选标签
r($customTester->getFeatureMenuTest($modules[1], $methods[1])) && p('0:name,text') && e('allstory,全部'); // 获取产品-需求列表筛选标签
r($customTester->getFeatureMenuTest($modules[2], $methods[2])) && p('0:name,text') && e('all,全部');      // 获取项目-项目列表筛选标签
r($customTester->getFeatureMenuTest($modules[3], $methods[3])) && p('0:name,text') && e('all,全部');      // 获取执行-任务列表筛选标签

zenData('lang')->gen(0);
