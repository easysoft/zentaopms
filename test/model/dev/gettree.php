#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';

su('admin');

/**

title=测试 devModel::getTree();
cid=1
pid=1

测试传入空值的情况 >> 0
测试传入错误类型的情况 >> 0
测试获取type=module模块树，并检查高亮情况 >> my,1
测试获取type=table模块树，并检查高亮情况 >> my,1

*/

global $tester;
$tester->loadModel('dev');

$activeList = array('', 'index', 'zt_todo');
$typeList   = array('', 'tree', 'module', 'table');
r($tester->dev->getTree($activeList[0], $typeList[0])) && p() && e('0');                  // 测试传入空值的情况
r($tester->dev->getTree($activeList[0], $typeList[1])) && p() && e('0');                  // 测试传入错误类型的情况
r($tester->dev->getTree($activeList[1], $typeList[2])) && p('0:key,active') && e('my,1'); // 测试获取type=module模块树，并检查高亮情况
r($tester->dev->getTree($activeList[2], $typeList[3])) && p('0:key,active') && e('my,1'); // 测试获取type=table模块树，并检查高亮情况
