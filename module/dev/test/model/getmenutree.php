#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

/**

title=测试 devModel::getMenuTree();
cid=0
pid=1

检查传入空值时的情况 >> 0
获取一级导航树 >> 0
获取二级导航树 >> my
获取二级导航树，并高亮地盘 >> my
获取二级导航树，并高亮地盘 >> 1
获取二级导航树，并高亮敏捷项目 >> my
获取三级导航树，并高亮地盘 >> project
获取筛选标签树，并高亮地盘 >> my

*/


$typeList     = array('', 'first', 'second', 'third', 'tag');
$moduleList   = array('', 'my', 'scrum', 'qa');
$methodList   = array('', 'work');

global $tester;
$tester->loadModel('dev');
r($tester->dev->getMenuTree($typeList[0], $moduleList[0], $methodList[0])) && p()                  && e('0');       // 检查传入空值时的情况
r($tester->dev->getMenuTree($typeList[1], $moduleList[0], $methodList[0])) && p()                  && e('0');       // 获取一级导航树
r($tester->dev->getMenuTree($typeList[2], $moduleList[0], $methodList[0])) && p('0:module')        && e('my');      // 获取二级导航树
r($tester->dev->getMenuTree($typeList[2], $moduleList[1], $methodList[0])) && p('0:module,active') && e('my');      // 获取二级导航树，并高亮地盘
r($tester->dev->getMenuTree($typeList[2], $moduleList[1], $methodList[1])) && p('0:module')        && e('1');       // 获取二级导航树，并高亮地盘
r($tester->dev->getMenuTree($typeList[2], $moduleList[2], $methodList[0])) && p('3:module')        && e('my');      // 获取二级导航树，并高亮敏捷项目
r($tester->dev->getMenuTree($typeList[3], $moduleList[1], $methodList[1])) && p('0:module')        && e('project'); // 获取三级导航树，并高亮地盘
r($tester->dev->getMenuTree($typeList[4], $moduleList[1], $methodList[1])) && p('0:module')        && e('my');      // 获取筛选标签树，并高亮地盘
