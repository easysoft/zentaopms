#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/datatable.class.php';
su('admin');

/**

title=测试 datatableModel::getSetting();
cid=1
pid=1

获取产品模块browse方法设置 >> ID
获取项目模块browse方法设置 >> 项目名称
获取执行模块task方法设置 >> 70
获取测试用例模块browse方法设置 >> auto

*/

$datatable = new datatableTest();
r($datatable->getSettingTest('product', 'browse'))  && p('0:title') && e('ID');       //获取产品模块browse方法设置
r($datatable->getSettingTest('project', 'browse'))  && p('1:title') && e('项目名称'); //获取项目模块browse方法设置
r($datatable->getSettingTest('execution', 'task'))  && p('0:width') && e('70');       //获取执行模块task方法设置
r($datatable->getSettingTest('testcase', 'browse')) && p('2:width') && e('auto');     //获取测试用例模块browse方法设置