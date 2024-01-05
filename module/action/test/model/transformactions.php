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

title=测试转换动态71 72 73 74 75 >> 分支1;这是一个模块1;这是测试套件名称1;这是测试套件名称1;2023-09-19EXECUTION
timeout=0
cid=101

- 测试转换动态1 2 3 4 5
 - 第1条的objectName属性 @正常产品1
 - 第2条的objectName属性 @用户需求1
 - 第3条的objectName属性 @1.0
 - 第4条的objectName属性 @产品正常的正常的发布1
 - 第5条的objectName属性 @项目集1
- 测试转换动态26 27 28 29 30
 - 第26条的objectName属性 @开发任务11
 - 第27条的objectName属性 @项目版本版本1
 - 第28条的objectName属性 @BUG1
 - 第29条的objectName属性 @这个是测试用例1
 - 第30条的objectName属性 @这个是测试用例1
- 测试转换动态51 52 53 54 55
 - 第51条的objectName属性 @测试单1
 - 第52条的objectName属性 @admin
 - 第53条的objectName属性 @文档标题1
 - 第54条的objectName属性 @产品主库
 - 第55条的objectName属性 @自定义1的待办
- 测试转换动态71 72 73 74 75
 - 第71条的objectName属性 @分支1
 - 第72条的objectName属性 @这是一个模块1
 - 第73条的objectName属性 @这是测试套件名称1
 - 第74条的objectName属性 @这是测试套件名称1
 - 第75条的objectName属性 @2023-09-19EXECUTION#101 迭代1 测试报告

*/

$actions = array('1,2,3,4,5', '26,27,28,29,30', '51,52,53,54,55', '71,72,73,74,75');

$action = new actionTest();

r($action->transformActionsTest($actions[0])) && p('1:objectName;2:objectName;3:objectName;4:objectName;5:objectName')       && e('正常产品1;用户需求1;1.0;产品正常的正常的发布1;项目集1');                                          // 测试转换动态1 2 3 4 5 
r($action->transformActionsTest($actions[1])) && p('26:objectName;27:objectName;28:objectName;29:objectName;30:objectName')  && e('开发任务11;项目版本版本1;BUG1;这个是测试用例1;这个是测试用例1');                                  // 测试转换动态26 27 28 29 30
r($action->transformActionsTest($actions[2])) && p('51:objectName;52:objectName;53:objectName;54:objectName;55:objectName')  && e('测试单1;admin;文档标题1;产品主库;自定义1的待办');                                                 // 测试转换动态51 52 53 54 55
r($action->transformActionsTest($actions[3])) && p('71:objectName;72:objectName;73:objectName;74:objectName;75:objectName')  && e('分支1;这是一个模块1;这是测试套件名称1;这是测试套件名称1;2023-09-19EXECUTION#101 迭代1 测试报告'); // 测试转换动态71 72 73 74 75