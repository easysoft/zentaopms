#!/usr/bin/env php
<?php

/**

title=测试 groupModel->updateView();
timeout=0
cid=1

- 验证views第views条的program属性 @program
- 验证programs第programs条的0属性 @1
- 验证products第products条的0属性 @1
- 验证sprints第sprints条的0属性 @1
- 验证actions第actions条的program属性 @create

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

$formData = array(
    'views'            => array('program' => 'on'),
    'programs'         => array(1),
    'products'         => array(1),
    'projects'         => array(1),
    'sprints'          => array(1),
    'actions'          => array('program' => array('create' => 'on')),
    'actionallchecker' => false,
);

$group = new groupTest();

r($group->updateViewTest(1, $formData)) && p('views:program')   && e('program'); // 验证views
r($group->updateViewTest(1, $formData)) && p('programs:0')      && e('1');       // 验证programs
r($group->updateViewTest(1, $formData)) && p('products:0')      && e('1');       // 验证products
r($group->updateViewTest(1, $formData)) && p('sprints:0')       && e('1');       // 验证sprints
r($group->updateViewTest(1, $formData)) && p('actions:program') && e('create');  // 验证actions
