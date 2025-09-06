#!/usr/bin/env php
<?php

/**

title=测试 actionModel::processActionForAPI();
cid=0

- 测试正常action处理，验证desc字段生成 @2023-01-01 10:00:00, 由 <strong>admin</strong> 创建。
- 测试对象类型参数转换 @2023-01-02 10:00:00, 由 <strong>user1</strong> 指派给 <strong>user2</strong>。
- 测试空数组输入 @0
- 测试actor字段用户映射 @admin
- 测试history字段名映射 @状态

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->gen(0);
zenData('user')->gen(0);
zenData('history')->gen(0);

su('admin');

$actionTest = new actionTest();

r($actionTest->processActionForAPITest(array((object)array('id' => 1, 'actor' => 'admin', 'action' => 'created', 'objectType' => 'task', 'objectID' => 1, 'date' => '2023-01-01 10:00:00', 'comment' => '', 'extra' => '', 'history' => false)), array('admin' => 'admin'), array())) && p('0:desc') && e('2023-01-01 10:00:00, 由 <strong>admin</strong> 创建。');
r($actionTest->processActionForAPITest((object)array('0' => (object)array('id' => 2, 'actor' => 'user1', 'action' => 'assigned', 'objectType' => 'task', 'objectID' => 2, 'date' => '2023-01-02 10:00:00', 'comment' => '', 'extra' => 'user2', 'history' => false)), array('user1' => 'user1', 'user2' => 'user2'), array())) && p('0:desc') && e('2023-01-02 10:00:00, 由 <strong>user1</strong> 指派给 <strong>user2</strong>。');
r($actionTest->processActionForAPITest(array(), array(), array())) && p() && e('0');
r($actionTest->processActionForAPITest(array((object)array('id' => 3, 'actor' => 'admin', 'action' => 'assigned', 'objectType' => 'task', 'objectID' => 3, 'date' => '2023-01-03 10:00:00', 'comment' => '', 'extra' => 'user1', 'history' => false)), array('admin' => 'admin', 'user1' => 'user1'), array())) && p('0:actor') && e('admin');
r($actionTest->processActionForAPITest(array((object)array('id' => 4, 'actor' => 'admin', 'action' => 'edited', 'objectType' => 'task', 'objectID' => 4, 'date' => '2023-01-04 10:00:00', 'comment' => '', 'extra' => '', 'history' => array((object)array('field' => 'status', 'old' => 'wait', 'new' => 'doing')))), array('admin' => 'admin'), array('status' => '状态'))) && p('0:history:0:fieldName') && e('状态');