#!/usr/bin/env php
<?php

/**

title=测试 groupModel->create();
timeout=0
cid=1

- 已有权限的分组新增一个权限
 - 第1条的0属性 @module1-method1
 - 第1条的1属性 @project-create
- 没有权限的分组新增一个权限第5条的0属性 @project-create

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

zdTable('group')->gen(5);
zdTable('grouppriv')->gen(4);
su('admin');

$insertPrivs = array((object)array('group' => 1, 'module' => 'project', 'method' => 'create'));
$newPrivs    = array((object)array('group' => 5, 'module' => 'project', 'method' => 'create'));

$group = new groupTest();

r($group->insertPrivsTest($insertPrivs)) && p('1:0;1:1') && e('module1-method1,project-create'); // 已有权限的分组新增一个权限
r($group->insertPrivsTest($newPrivs))    && p('5:0')     && e('project-create');                 // 没有权限的分组新增一个权限
