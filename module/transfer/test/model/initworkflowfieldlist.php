#!/usr/bin/env php
<?php

/**

title=测试 transferModel::initWorkflowFieldList();
timeout=0
cid=19326

- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'', array  @0
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'story', array  @0
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'task', array  @1
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'user', array  @1
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'project', array  @1
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'bug', array  @1
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'testcase', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transfer.unittest.class.php';

su('admin');

$transferTest = new transferTest();

r($transferTest->initWorkflowFieldListTest('', array())) && p() && e('0');
r($transferTest->initWorkflowFieldListTest('story', array())) && p() && e('0');
r($transferTest->initWorkflowFieldListTest('task', array('title' => array('name' => 'title', 'label' => 'Title')))) && p() && e('1');
r($transferTest->initWorkflowFieldListTest('user', array('account' => array('name' => 'account', 'label' => 'Account')))) && p() && e('1');
r($transferTest->initWorkflowFieldListTest('project', array('name' => array('name' => 'name', 'label' => 'Name', 'control' => 'input')))) && p() && e('1');
r($transferTest->initWorkflowFieldListTest('bug', array('title' => array('name' => 'title', 'label' => 'Bug Title', 'control' => 'input')))) && p() && e('1');
r($transferTest->initWorkflowFieldListTest('testcase', array('name' => array('name' => 'name', 'label' => 'Case Name', 'required' => false)))) && p() && e('1');