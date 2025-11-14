#!/usr/bin/env php
<?php

/**

title=测试 groupModel::updatePrivByModule();
timeout=0
cid=16725

- 执行group模块的updatePrivByModuleTest方法，参数是'my', array 属性my @index|work
- 执行group模块的updatePrivByModuleTest方法，参数是'task', array 属性task @create
- 执行group模块的updatePrivByModuleTest方法，参数是'bug', array 属性bug @browse|view
- 执行group模块的updatePrivByModuleTest方法，参数是'story', array 属性story @create|edit
- 执行group模块的updatePrivByModuleTest方法，参数是'product', array 属性product @browse
- 执行group模块的updatePrivByModuleTest方法，参数是'doc', array 属性doc @edit|view
- 执行group模块的updatePrivByModuleTest方法，参数是'my', array 属性my @index|work

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

zenData('group')->loadYaml('group')->gen(5);
zenData('grouppriv')->loadYaml('grouppriv')->gen(10);

su('admin');

$group = new groupTest();

r($group->updatePrivByModuleTest('my', array(1, 2), array('my-index', 'my-work'))[1]) && p('my') && e('index|work');
r($group->updatePrivByModuleTest('task', array(3), array('task-create'))[3]) && p('task') && e('create');
r($group->updatePrivByModuleTest('bug', array(2), array('bug-browse', '', 'bug-view'))[2]) && p('bug') && e('browse|view');
r($group->updatePrivByModuleTest('story', array(1), array('story-create', 'invalid-format', 'story-edit'))[1]) && p('story') && e('create|edit');
r($group->updatePrivByModuleTest('product', array(1), array('product-browse'))[1]) && p('product') && e('browse');
r($group->updatePrivByModuleTest('doc', array(2), array('doc-view', 'doc-edit'))[2]) && p('doc') && e('edit|view');
r($group->updatePrivByModuleTest('my', array(1), array('my-index'))[1]) && p('my') && e('index|work');