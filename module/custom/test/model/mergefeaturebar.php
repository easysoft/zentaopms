#!/usr/bin/env php
<?php

/**

title=测试 customModel->mergeFeatureBar();
timeout=0
cid=15918

- 获取地盘-待处理-任务列表筛选标签 @0
- 获取未关闭标签属性unclosed @未关闭
- 获取全部标签属性all @全部
- 获取由我指派标签属性assignedbyme @由我指派
- 获取更多标签属性status @更多

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('userquery')->gen(0);
zenData('user')->gen(5);
su('admin');

$modules = array('my', 'product', 'project', 'execution');
$methods = array('work', 'browse', 'browse', 'task');

$customTester  = new customModelTest();
r($customTester->mergeFeatureBarTest($modules[0], $methods[0])) && p()               && e('0');        // 获取地盘-待处理-任务列表筛选标签
r($customTester->mergeFeatureBarTest($modules[1], $methods[1])) && p('unclosed')     && e('未关闭');   // 获取未关闭标签
r($customTester->mergeFeatureBarTest($modules[2], $methods[2])) && p('all')          && e('全部');     // 获取全部标签
r($customTester->mergeFeatureBarTest($modules[3], $methods[3])) && p('assignedbyme') && e('由我指派'); // 获取由我指派标签
r($customTester->mergeFeatureBarTest($modules[3], $methods[3])) && p('status')       && e('更多');     // 获取更多标签

zenData('lang')->gen(0);