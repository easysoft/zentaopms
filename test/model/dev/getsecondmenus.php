#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';

/**

title=测试 devModel::getSecondMenus();
cid=1
pid=1

检查传入空值时的情况   >> 0
检查没有二级菜单的模块 >> 0
检查有二级菜单的模块   >> 项目通用,0

*/


$moduleList     = array('', 'my', 'project');

global $tester;
$tester->loadModel('dev');
r($tester->dev->getSecondMenus($moduleList[0])) && p()                 && e('0');          // 检查传入空值时的情况
r($tester->dev->getSecondMenus($moduleList[1])) && p()                 && e('0');          // 检查没有二级菜单的模块
r($tester->dev->getSecondMenus($moduleList[2])) && p('0:title,active') && e('项目通用,0'); // 检查有二级菜单的模块
