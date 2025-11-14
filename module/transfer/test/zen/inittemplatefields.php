#!/usr/bin/env php
<?php

/**

title=测试 transferZen::initTemplateFields();
timeout=0
cid=19338

- 执行transferTest模块的initTemplateFieldsTest方法，参数是'user', 'account, realname, email' 属性module @user
- 执行transferTest模块的initTemplateFieldsTest方法，参数是'task', 'name, pri, estimate' 属性module @task
- 执行transferTest模块的initTemplateFieldsTest方法，参数是'bug', 'title, severity, type' 属性module @bug
- 执行transferTest模块的initTemplateFieldsTest方法，参数是'story', 'title, pri, estimate' 属性module @story
- 执行transferTest模块的initTemplateFieldsTest方法，参数是'product', 'name, code, type' 属性module @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

zenData('user')->gen(10);
zenData('product')->gen(5);
zenData('project')->gen(3);

su('admin');

global $tester;
$tester->app->methodName = 'showimport';

$transferTest = new transferZenTest();

r($transferTest->initTemplateFieldsTest('user', 'account,realname,email')) && p('module') && e('user');
r($transferTest->initTemplateFieldsTest('task', 'name,pri,estimate')) && p('module') && e('task');
r($transferTest->initTemplateFieldsTest('bug', 'title,severity,type')) && p('module') && e('bug');
r($transferTest->initTemplateFieldsTest('story', 'title,pri,estimate')) && p('module') && e('story');
r($transferTest->initTemplateFieldsTest('product', 'name,code,type')) && p('module') && e('product');