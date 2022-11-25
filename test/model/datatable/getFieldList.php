#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/datatable.class.php';
su('admin');

/**

title=测试 datatableModel::getFieldList();
cid=1
pid=1

获取产品模块browse方法自定义列 >> ID
获取项目模块browse方法自定义列 >> 项目名称
获取执行模块task方法自定义列 >> 70
获取测试用例模块browse方法自定义列 >> auto

*/

$datatable = new datatableTest();
r($datatable->getFieldListTest('product', 'browse'))  && p('id:title')    && e('ID');       //获取产品模块browse方法自定义列
r($datatable->getFieldListTest('project', 'browse'))  && p('name:title')  && e('项目名称'); //获取项目模块browse方法自定义列
r($datatable->getFieldListTest('execution', 'task'))  && p('id:width')    && e('70');       //获取执行模块task方法自定义列
r($datatable->getFieldListTest('testcase', 'browse')) && p('title:width') && e('auto');     //获取测试用例模块browse方法自定义列