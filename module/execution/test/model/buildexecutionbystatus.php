#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

/**
title=测试executionModel->buildExecutionByStatus();
cid=1
pid=1
*/

$execution  = new executionTest();
r($execution->buildExecutionByStatusTest('wait'))      && p('status,closedBy,canceledBy') && e("wait,~~,~~");   // 测试传入未开始状态
r($execution->buildExecutionByStatusTest('doing'))     && p('status,closedBy,canceledBy') && e("doing,~~,~~");  // 测试传入进行中状态
r($execution->buildExecutionByStatusTest('suspended')) && p('status,closedBy')            && e('suspended,~~'); // 测试传入已挂起状态
r($execution->buildExecutionByStatusTest('closed'))    && p('status,closedBy')            && e('closed,admin'); // 测试传入已关闭状态
