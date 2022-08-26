#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/datatable.class.php';
su('admin');

/**

title=测试 datatableModel::setFixedFieldWidth();
cid=1
pid=1

获取产品模块browse方法设置宽度 >> 550
获取项目模块browse方法设置宽度 >> 180
获取执行模块browse方法设置宽度 >> 550
获取测试用例模块browse方法设置宽度 >> 150

*/

$datatable = new datatableTest();
$productSetting   = $datatable->getSettingTest('product', 'browse');
$projectSetting   = $datatable->getSettingTest('project', 'browse');
$executionSetting = $datatable->getSettingTest('execution', 'task');
$testcaseSetting  = $datatable->getSettingTest('testcase', 'browse');

r($datatable->setFixedFieldWidthTest($productSetting))   && p('leftWidth')  && e('550'); //获取产品模块browse方法设置宽度
r($datatable->setFixedFieldWidthTest($projectSetting))   && p('rightWidth') && e('180'); //获取项目模块browse方法设置宽度
r($datatable->setFixedFieldWidthTest($executionSetting)) && p('leftWidth')  && e('550'); //获取执行模块browse方法设置宽度
r($datatable->setFixedFieldWidthTest($testcaseSetting))  && p('rightWidth') && e('150'); //获取测试用例模块browse方法设置宽度