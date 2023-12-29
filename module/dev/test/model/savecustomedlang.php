#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dev.class.php';

zdTable('config')->gen(0);

/**

title=测试 devModel']']saveCustomedLang();
cid=1
pid=1

自定义公共语言项 >> 产品2|项目2|迭代2
自定义一级菜单语言项 >> 我的地盘
重置一级导航语言项 >> null
自定义二级导航语言项 >> 仪表盘
重置二级导航语言项 >> null
自定义三级导航语言项 >> 任务
重置三级导航语言项 >> null
自定义检索标签语言项 >> 所有
重置检索标签语言项 >> null

*/

global $config;
$config->custom->URSR = 1;

$devTester = new devTest();

$_POST = array();
$_POST['common_productCommon']   = '产品2';
$_POST['common_projectCommon']   = '项目2';
$_POST['common_executionCommon'] = '迭代2';
$_POST['common_URCommon']        = '用需2';
$_POST['common_SRCommon']        = '软需2';
r($devTester->saveCustomedLangTest('common', 'common', '', 'zh-cn')) && p()  && e("产品2|项目2|迭代2|");    // 自定义公共语言项

$_POST = array();
$_POST['common_mainNav_my'] = '我的地盘';
r($devTester->saveCustomedLangTest('first', 'common', '', 'zh-cn')) && p()  && e("我的地盘");    // 自定义一级菜单语言项

$_POST['common_mainNav_my'] = '';
r($devTester->saveCustomedLangTest('first', 'common', '', 'zh-cn')) && p()  && e("null");        // 重置一级导航语言项

$_POST = array();
$_POST['myMenu_menu_index'] = '仪表盘';
r($devTester->saveCustomedLangTest('second', 'my', '', 'zh-cn')) && p()  && e("仪表盘");         // 自定义二级导航语言项

$_POST['myMenu_menu_index'] = '';
r($devTester->saveCustomedLangTest('second', 'my', '', 'zh-cn')) && p()  && e("null");           // 重置二级导航语言项

$_POST = array();
$_POST['mySubMenu_work_task'] = '任务';
r($devTester->saveCustomedLangTest('third', 'my', 'work', 'zh-cn')) && p()  && e("任务");        // 自定义三级导航语言项

$_POST['mySubMenu_work_task'] = '';
r($devTester->saveCustomedLangTest('third', 'my', 'work', 'zh-cn')) && p()  && e("null");        // 重置三级导航语言项

$_POST = array();
$_POST['my_featureBar-todo_all'] = '所有';
r($devTester->saveCustomedLangTest('tag', 'my', 'todo', 'zh-cn')) && p()  && e("所有");          // 自定义检索标签语言项

$_POST['my_featureBar-todo_all'] = '';
r($devTester->saveCustomedLangTest('tag', 'my', 'todo', 'zh-cn')) && p()  && e("null");          // 重置检索标签语言项
