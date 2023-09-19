#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(25);
zdTable('product')->gen(1);
zdTable('story')->gen(1);
zdTable('productplan')->gen(1);
zdTable('release')->gen(1);
zdTable('project')->gen(1);
zdTable('task')->gen(1);
zdTable('build')->config('build')->gen(1);
zdTable('bug')->gen(1);
zdTable('case')->gen(1);
zdTable('case')->gen(1);
zdTable('testtask')->gen(1);
zdTable('user')->gen(1);
zdTable('doc')->gen(1);
zdTable('doclib')->gen(1);
zdTable('todo')->gen(1);
zdTable('branch')->gen(1);
zdTable('module')->gen(1);
zdTable('testsuite')->gen(1);
zdTable('testsuite')->config('testsuite')->gen(1);
zdTable('testreport')->gen(1);
zdTable('product')->gen(1);

/**

title=测试 actionModel->transformActions();
cid=1
pid=1

测试转换动态1 2 3 4 5      >> 正常产品1,用户需求1,1.0,产品正常的正常的发布1,项目集1
测试转换动态26 27 28 29 30 >> 开发任务11;项目版本版本1;BUG1;这个是测试用例1;这个是测试用例1
测试转换动态51 52 53 54 55 >> 测试单1;admin;文档标题1;产品主库;自定义1的待办
测试转换动态71 72 73 74 75 >> 分支1;这是一个模块1;这是测试套件名称1;这是测试套件名称1;2023-09-19EXECUTION#101 迭代1 测试报告

*/

$actions = array('1,2,3,4,5', '26,27,28,29,30', '51,52,53,54,55', '71,72,73,74,75');

$action = new actionTest();

r($action->transformActionsTest($actions[0])) && p('1:objectName;2:objectName;3:objectName;4:objectName;5:objectName')       && e('正常产品1;用户需求1;1.0;产品正常的正常的发布1;项目集1');                                          // 测试转换动态1 2 3 4 5 
r($action->transformActionsTest($actions[1])) && p('26:objectName;27:objectName;28:objectName;29:objectName;30:objectName')  && e('开发任务11;项目版本版本1;BUG1;这个是测试用例1;这个是测试用例1');                                  // 测试转换动态26 27 28 29 30
r($action->transformActionsTest($actions[2])) && p('51:objectName;52:objectName;53:objectName;54:objectName;55:objectName')  && e('测试单1;admin;文档标题1;产品主库;自定义1的待办');                                                 // 测试转换动态51 52 53 54 55
r($action->transformActionsTest($actions[3])) && p('71:objectName;72:objectName;73:objectName;74:objectName;75:objectName')  && e('分支1;这是一个模块1;这是测试套件名称1;这是测试套件名称1;2023-09-19EXECUTION#101 迭代1 测试报告'); // 测试转换动态71 72 73 74 75
