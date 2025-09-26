#!/usr/bin/env php
<?php

/**

title=测试 actionModel::processActionForAPI();
timeout=0
cid=0

- 执行actionTest模块的processActionForAPITest方法，参数是array 
 - 第0条的desc属性 @2023-01-01 10:00:00
- 执行actionTest模块的processActionForAPITest方法，参数是array 
 - 第0条的desc属性 @2023-01-02 10:00:00
- 执行actionTest模块的processActionForAPITest方法，参数是array  @Array\n(\n)\n
- 执行actionTest模块的processActionForAPITest方法，参数是array 第0条的actor属性 @admin
- 执行actionTest模块的processActionForAPITest方法，参数是array 第0条的history:0:fieldName属性 @状态

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->gen(0);
zenData('user')->gen(0);
zenData('history')->gen(0);

su('admin');

$actionTest = new actionTest();

r($actionTest->processActionForAPITest(array((object)array('id' => 1, 'actor' => 'admin', 'action' => 'created', 'objectType' => 'task', 'objectID' => 1, 'date' => '2023-01-01 10:00:00', 'comment' => '', 'extra' => '', 'history' => false)), array('admin' => 'admin'), array())) && p('0:desc') && e('2023-01-01 10:00:00, 由 <strong>admin</strong> 创建。\n');
r($actionTest->processActionForAPITest(array((object)array('id' => 2, 'actor' => 'user1', 'action' => 'assigned', 'objectType' => 'task', 'objectID' => 2, 'date' => '2023-01-02 10:00:00', 'comment' => '', 'extra' => 'user2', 'history' => false)), array('user1' => 'user1', 'user2' => 'user2'), array())) && p('0:desc') && e('2023-01-02 10:00:00, 由 <strong>user1</strong> 指派给 <strong>user2</strong>。\n');
r($actionTest->processActionForAPITest(array(), array(), array())) && p() && e('Array\n(\n)\n');
r($actionTest->processActionForAPITest(array((object)array('id' => 3, 'actor' => 'admin', 'action' => 'assigned', 'objectType' => 'task', 'objectID' => 3, 'date' => '2023-01-03 10:00:00', 'comment' => '', 'extra' => 'user1', 'history' => false)), array('admin' => 'admin', 'user1' => 'user1'), array())) && p('0:actor') && e('admin');
r($actionTest->processActionForAPITest(array((object)array('id' => 4, 'actor' => 'admin', 'action' => 'edited', 'objectType' => 'task', 'objectID' => 4, 'date' => '2023-01-04 10:00:00', 'comment' => '', 'extra' => '', 'history' => array((object)array('field' => 'status', 'old' => 'wait', 'new' => 'doing')))), array('admin' => 'admin'), array('status' => '状态'))) && p('0:history:0:fieldName') && e('状态');