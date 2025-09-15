#!/usr/bin/env php
<?php

/**

title=测试 transferModel::initWorkflowFieldList();
timeout=0
cid=0

- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'', array  @0
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'story', array  @0
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'task', array 第title条的name属性 @title
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'user', array 第account条的name属性 @account
- 执行transferTest模块的initWorkflowFieldListTest方法，参数是'project', array 第name条的control属性 @input

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transfer.unittest.class.php';

su('admin');

$transferTest = new transferTest();

r($transferTest->initWorkflowFieldListTest('', array())) && p() && e(0);
r($transferTest->initWorkflowFieldListTest('story', array())) && p() && e(0);
r($transferTest->initWorkflowFieldListTest('task', array('title' => array('name' => 'title', 'label' => 'Title')))) && p('title:name') && e('title');
r($transferTest->initWorkflowFieldListTest('user', array('account' => array('name' => 'account', 'label' => 'Account')))) && p('account:name') && e('account');
r($transferTest->initWorkflowFieldListTest('project', array('name' => array('name' => 'name', 'label' => 'Name', 'control' => 'input')))) && p('name:control') && e('input');