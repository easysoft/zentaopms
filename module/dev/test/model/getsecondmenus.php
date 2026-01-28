#!/usr/bin/env php
<?php

/**

title=测试 devModel::getSecondMenus();
timeout=0
cid=16010

- 测试步骤1：空菜单参数情况 @0
- 测试步骤2：非project菜单情况 @0
- 测试步骤3：project菜单基本情况第0条的title属性 @项目通用
- 测试步骤4：project菜单匹配模块情况第0条的active属性 @1
- 测试步骤5：project菜单返回数组长度验证 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$moduleList = array('', 'my', 'project');

global $tester;
$tester->loadModel('dev');

r($tester->dev->getSecondMenus($moduleList[0])) && p() && e('0'); // 测试步骤1：空菜单参数情况
r($tester->dev->getSecondMenus($moduleList[1])) && p() && e('0'); // 测试步骤2：非project菜单情况
r($tester->dev->getSecondMenus($moduleList[2])) && p('0:title') && e('项目通用'); // 测试步骤3：project菜单基本情况
r($tester->dev->getSecondMenus($moduleList[2], 'project', '')) && p('0:active') && e('1'); // 测试步骤4：project菜单匹配模块情况
r(count($tester->dev->getSecondMenus($moduleList[2]))) && p() && e('4'); // 测试步骤5：project菜单返回数组长度验证