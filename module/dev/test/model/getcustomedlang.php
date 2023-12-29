#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dev.class.php';

su('admin');

$ztLang = zdTable('lang');
$ztLang->id->range('1-3');
$ztLang->lang->range('zh-cn');
$ztLang->module->range('common,myMenu,my');
$ztLang->section->range('``,menu,featureBar-todo');
$ztLang->key->range('productCommon,index,all');
$ztLang->value->range('1-3')->prefix('测试');
$ztLang->gen(3);

zdTable('config')->gen(0);

/**

title=测试 devModel::getCustomedLang();
cid=1
pid=1

错误的类型返回数据                         >> 0
正确的类型返回数据                         >> 测试1
正确的类型，错误的模块返回数据             >> 0
正确的类型，正确的模块返回数据             >> 测试2
正确的类型，正确的模块，错误的方法返回数据 >> 0
正确的类型，正确的模块，正确的方法返回数据 >> 测试3

*/

global $config;
$config->custom->URSR = 1;

$failModule = 'module';
$realModule = 'my';

$failMethod = 'method';
$realMethod = 'todo';

$devTester = new devTest();
r($devTester->getCustomedLangTest('test'))   && p()                && e("0");     // 错误的类型返回数据
r($devTester->getCustomedLangTest('common')) && p('productCommon') && e("测试1"); // 正确的类型返回数据

r($devTester->getCustomedLangTest('second', $failModule)) && p()             && e("0");     // 正确的类型，错误的模块返回数据
r($devTester->getCustomedLangTest('second', $realModule)) && p('menu_index') && e("测试2"); // 正确的类型，正确的模块返回数据

r($devTester->getCustomedLangTest('tag', $realModule, $failMethod)) && p()                      && e("0");     // 正确的类型，正确的模块，错误的方法返回数据
r($devTester->getCustomedLangTest('tag', $realModule, $realMethod)) && p('featureBar-todo_all') && e("测试3"); // 正确的类型，正确的模块，正确的方法返回数据
