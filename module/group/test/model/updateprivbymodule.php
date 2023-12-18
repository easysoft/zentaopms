#!/usr/bin/env php
<?php

/**

title=测试 groupModel->updatePrivByModule();
timeout=0
cid=1

- group1,2 的my模块添加index,work方法，验证group1属性my @index|work
- group1,2 的my模块添加index,work方法，验证group2属性my @index|work
- group1,2 的my模块额外添加index,doc方法， 验证group1属性my @doc|index|work

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

/*
 * 生成的权限如下：
 * group 1: module1-method1, module6-method6
 * group 2: module2-method2, module7-method7
 */
zdTable('group')->config('group')->gen(5);
zdTable('grouppriv')->config('grouppriv')->gen(10);

$group = new groupTest();
$module = 'my';
$groups = array(1, 2);
$actions1 = array('my-index', 'my-work');
$actions2 = array('my-index', 'my-doc');

r($group->updatePrivByModuleTest($module, $groups, $actions1)[1]) && p('my') && e('index|work');     //group1,2 的my模块添加index,work方法，验证group1
r($group->updatePrivByModuleTest($module, $groups, $actions1)[2]) && p('my') && e('index|work');     //group1,2 的my模块添加index,work方法，验证group2
r($group->updatePrivByModuleTest($module, $groups, $actions2)[1]) && p('my') && e('doc|index|work'); //group1,2 的my模块额外添加index,doc方法， 验证group1