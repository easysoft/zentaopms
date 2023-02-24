#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

/**

title=测试executionModel->buildExecutionByStatus();
cid=1
pid=1

测试传入未开始状态，返回对象的实际开始时间 >> 0
测试传入进行中状态，返回对象的状态 >> doing
测试传入已挂起状态，返回对象的状态 >> suspended
测试传入已关闭状态，返回对象的由谁关闭 >> admin

*/

$execution  = new executionTest();
$waitObject = $execution->buildExecutionByStatusTest('wait');
r($waitObject->realBegan)                              && p('')          && e('0');           // 测试传入未开始状态，返回对象的实际开始时间
r($execution->buildExecutionByStatusTest('doing'))     && p('status')    && e("doing");       // 测试传入进行中状态，返回对象的状态
r($execution->buildExecutionByStatusTest('suspended')) && p('status')    && e('suspended');   // 测试传入已挂起状态，返回对象的状态
r($execution->buildExecutionByStatusTest('closed'))    && p('closedBy')  && e('admin');       // 测试传入已关闭状态，返回对象的由谁关闭
