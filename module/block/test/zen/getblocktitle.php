#!/usr/bin/env php
<?php

/**

title=测试 blockZen::getBlockTitle();
timeout=0
cid=15242

- 执行blockTest模块的getBlockTitleTest方法，参数是array  @正常的产品列表
- 执行blockTest模块的getBlockTitleTest方法，参数是array  @测试列表
- 执行blockTest模块的getBlockTitleTest方法，参数是array  @进行中的我的任务
- 执行blockTest模块的getBlockTitleTest方法，参数是array  @用户
- 执行blockTest模块的getBlockTitleTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

r($blockTest->getBlockTitleTest(array('product' => '产品', 'project' => '项目', 'task' => '任务'), 'product', array('list' => '产品列表'), 'list', array('type' => array('options' => array('normal' => '正常'))))) && p() && e('正常的产品列表');
r($blockTest->getBlockTitleTest(array('scrumtest' => '敏捷测试'), 'scrumtest', array('test' => '测试列表'), 'test', array())) && p() && e('测试列表');
r($blockTest->getBlockTitleTest(array('task' => '任务'), 'task', array('mytask' => '我的任务'), 'mytask', array('type' => array('options' => array('doing' => '进行中'))))) && p() && e('进行中的我的任务');
r($blockTest->getBlockTitleTest(array('user' => '用户', 'bug' => 'Bug'), 'user', array(), 'welcome', array())) && p() && e('用户');
r($blockTest->getBlockTitleTest(array('product' => '产品', 'project' => '项目'), 'product', array(), 'welcome', array())) && p() && e('0');