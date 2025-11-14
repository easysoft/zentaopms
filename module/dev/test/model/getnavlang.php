#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

/**

title=测试 devModel::getNavLang();
cid=16008
pid=1

检查传入空值时的情况 >> <i class='icon icon-menu-my'></i> 地盘|my|index|
检查获取一级导航 >> <i class='icon icon-program'></i> 项目集|program|browse|
检查获取地盘二级导航 >> 仪表盘|my|index
检查获取敏捷项目二级英文导航 >> Dashboard|project|index|project=%s
检查获取瀑布项目二级中文导航 >> 仪表盘|project|index|project=%s
检查获取测试二级英文导航 >> Dashboard|qa|index
检查获取三级导航 >> <i class='icon icon-menu-my'></i> 地盘|my|index|
检查获取地盘待处理三级导航 >> 任务|my|work|mode=task
检查获取项目设置三级导航 >> 概况|project|view|project=%s
检查获取项目测试英文三级导航 >> Bug|project|bug|projectID=%s
检查传入不存在的type >> <i class='icon icon-menu-my'></i> 地盘|my|index|

*/


$typeList     = array('', 'first', 'second', 'third', 'tag');
$moduleList   = array('', 'my', 'scrum', 'waterfall', 'qa');
$methodList   = array('', 'work', 'settings', 'qa', 'bug');
$languageList = array('', 'zh-cn', 'en');

$devTester = new devTest();
r($devTester->getNavLangTest($typeList[0], $moduleList[0], $methodList[0], $languageList[0])) && p('my')              && e("<i class='icon icon-menu-my'></i> 地盘|my|index|");         // 检查传入空值时的情况
r($devTester->getNavLangTest($typeList[1], $moduleList[0], $methodList[0], $languageList[1])) && p('program')         && e("<i class='icon icon-program'></i> 项目集|program|browse|"); // 检查获取一级导航
r($devTester->getNavLangTest($typeList[2], $moduleList[1], $methodList[0], $languageList[1])) && p('menu_index:link') && e("仪表盘|my|index");                                          // 检查获取地盘二级导航
r($devTester->getNavLangTest($typeList[2], $moduleList[2], $methodList[0], $languageList[2])) && p('menu_index:link') && e("Dashboard|project|index|project=%s");                       // 检查获取敏捷项目二级英文导航
r($devTester->getNavLangTest($typeList[2], $moduleList[3], $methodList[0], $languageList[1])) && p('menu_index:link') && e("仪表盘|project|index|project=%s");                          // 检查获取瀑布项目二级中文导航
r($devTester->getNavLangTest($typeList[2], $moduleList[4], $methodList[0], $languageList[2])) && p('menu_index:link') && e("Dashboard|qa|index");                                       // 检查获取测试二级英文导航
r($devTester->getNavLangTest($typeList[3], $moduleList[0], $methodList[0], $languageList[0])) && p('my')              && e("<i class='icon icon-menu-my'></i> 地盘|my|index|");         // 检查获取三级导航
r($devTester->getNavLangTest($typeList[3], $moduleList[1], $methodList[1], $languageList[0])) && p('task:link')       && e("任务|my|work|mode=task");                                   // 检查获取地盘待处理三级导航
r($devTester->getNavLangTest($typeList[3], $moduleList[2], $methodList[2], $languageList[0])) && p('view:link')       && e("概况|project|view|project=%s");                             // 检查获取项目设置三级导航
r($devTester->getNavLangTest($typeList[3], $moduleList[3], $methodList[3], $languageList[2])) && p('bug:link')        && e("Bug|project|bug|projectID=%s");                             // 检查获取项目测试英文三级导航
r($devTester->getNavLangTest($typeList[4], $moduleList[0], $methodList[0], $languageList[0])) && p('my')              && e("<i class='icon icon-menu-my'></i> 地盘|my|index|");         // 检查传入不存在的type
