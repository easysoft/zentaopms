#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dev.class.php';

zdTable('config')->gen(0);

/**

title=测试 devModel::getOriginalLang();
cid=1
pid=1

错误的类型返回数据                         >> 地盘
正确的类型返回数据                         >> 项目
正确的类型，错误的模块返回数据             >> 0
正确的类型，正确的模块返回数据             >> 人员
正确的类型，正确的模块，错误的方法返回数据 >> 0
正确的类型，正确的模块，正确的方法返回数据 >> 全部

*/

global $config;
$config->custom->URSR = 1;

$failModule = 'module';
$realModule = 'program';

$failMethod = 'method';
$realMethod = 'browse';

$devTester = new devTest();
r($devTester->getOriginalLangTest('test'))   && p('my')            && e("地盘"); // 错误的类型返回数据
r($devTester->getOriginalLangTest('common')) && p('projectCommon') && e("项目"); // 正确的类型返回数据

r($devTester->getOriginalLangTest('second', $failModule)) && p()                 && e("0");    // 正确的类型，错误的模块返回数据
r($devTester->getOriginalLangTest('second', $realModule)) && p('menu_personnel') && e("人员"); // 正确的类型，正确的模块返回数据

r($devTester->getOriginalLangTest('tag', $realModule, $failMethod)) && p()                        && e("0");    // 正确的类型，正确的模块，错误的方法返回数据
r($devTester->getOriginalLangTest('tag', $realModule, $realMethod)) && p('featureBar-browse_all') && e("全部"); // 正确的类型，正确的模块，正确的方法返回数据
