#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getRelatedPrivs();
timeout=0
cid=1

- 选中my-todo权限，会提示依赖my和todo模块
 - 第depend条的my属性 @my
 - 第depend条的todo属性 @todo
- 选中my-todo权限，会推荐todo模块的方法
 - 第recommend条的my属性 @` `
 - 第recommend条的todo属性 @todo
- 选中my-todo权限，已选中推荐方法my-index，那么my模块也会出现在推荐中
 - 第depend条的my属性 @my
 - 第depend条的todo属性 @todo

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

$group = new groupTest();
$allPrivs = array('my-index', 'my-calendar', 'my-todo', 'todo-view', 'todo-calendar');

r($group->getRelatedPrivsTest($allPrivs, array('my-todo'), array())) && p('depend:my,todo')           && e('my,todo');  // 选中my-todo权限，会提示依赖my和todo模块
r($group->getRelatedPrivsTest($allPrivs, array('my-todo'), array())) && p('recommend:my,todo')        && e('` `,todo'); // 选中my-todo权限，会推荐todo模块的方法
r($group->getRelatedPrivsTest($allPrivs, array('my-todo'), array('my-index'))) && p('depend:my,todo') && e('my,todo');  // 选中my-todo权限，已选中推荐方法my-index，那么my模块也会出现在推荐中