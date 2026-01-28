#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=测试 devModel::getTree();
cid=16014

- 测试传入空值的情况 @0
- 测试传入错误类型的情况 @0
- 测试获取type=module，active空
 - 第0条的key属性 @my
 - 第0条的active属性 @0
- 测试获取type=module模块树，并检查高亮情况
 - 第0条的key属性 @my
 - 第0条的active属性 @1
- 测试获取type=table模块树，并检查高亮情况
 - 第0条的key属性 @my
 - 第0条的active属性 @1

*/

global $tester;
$devModel = $tester->loadModel('dev');
$devModel->app->moduleName = 'dev';
$devModel->app->methodName = 'index';

$activeList = array('', 'index', 'zt_todo');
$typeList   = array('', 'tree', 'module', 'table');
r($devModel->getTree($activeList[0], $typeList[0])) && p() && e('0');                  // 测试传入空值的情况
r($devModel->getTree($activeList[0], $typeList[1])) && p() && e('0');                  // 测试传入错误类型的情况
r($devModel->getTree($activeList[0], $typeList[2])) && p('0:key,active') && e('my,0'); // 测试获取type=module，active空
r($devModel->getTree($activeList[1], $typeList[2])) && p('0:key,active') && e('my,1'); // 测试获取type=module模块树，并检查高亮情况
r($devModel->getTree($activeList[2], $typeList[3])) && p('0:key,active') && e('my,1'); // 测试获取type=table模块树，并检查高亮情况
