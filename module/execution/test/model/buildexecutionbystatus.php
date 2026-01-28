#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

/**
title=测试executionModel->buildExecutionByStatus();
cid=16272

- 测试传入未开始状态
 - 属性status @wait
 - 属性closedBy @~~
 - 属性canceledBy @~~
- 测试传入进行中状态
 - 属性status @doing
 - 属性closedBy @~~
 - 属性canceledBy @~~
- 测试传入已挂起状态
 - 属性status @suspended
 - 属性closedBy @~~
- 测试传入已关闭状态
 - 属性status @closed
 - 属性closedBy @admin
- 测试传入空状态 @~~

*/

$execution  = new executionModelTest();
r($execution->buildExecutionByStatusTest('wait'))      && p('status,closedBy,canceledBy') && e("wait,~~,~~");   // 测试传入未开始状态
r($execution->buildExecutionByStatusTest('doing'))     && p('status,closedBy,canceledBy') && e("doing,~~,~~");  // 测试传入进行中状态
r($execution->buildExecutionByStatusTest('suspended')) && p('status,closedBy')            && e('suspended,~~'); // 测试传入已挂起状态
r($execution->buildExecutionByStatusTest('closed'))    && p('status,closedBy')            && e('closed,admin'); // 测试传入已关闭状态
r($execution->buildExecutionByStatusTest(''))          && p()                             && e('~~');           // 测试传入空状态
