#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';

/**

title=测试 devModel::getTagMenus();
cid=1
pid=1

检查传入空值时的情况 >> 0
检查地盘高亮情况 >> 1
检查项目高亮情况 >> 1

*/


$moduleList     = array('', 'my', 'scrum');
$moduleNameList = array('', 'my', 'project');
$methodList     = array('', 'todo', 'execution');

global $tester;
$tester->loadModel('dev');
r($tester->dev->getTagMenus($moduleList[0], $moduleNameList[0], $methodList[0])) && p()                   && e('0'); // 检查传入空值时的情况
r($tester->dev->getTagMenus($moduleList[1], $moduleNameList[1], $methodList[1])) && p('calendar:active')  && e('1'); // 检查地盘高亮情况
r($tester->dev->getTagMenus($moduleList[2], $moduleNameList[2], $methodList[2])) && p('execution:active') && e('1'); // 检查项目高亮情况
