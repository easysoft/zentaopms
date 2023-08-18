#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->reportCondition();
timeout=0
cid=1

*/

$taskTester = new taskTest();

$sql = "execution  = '4' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0'";
r($taskTester->reportConditionTest())     && p() && e('1=1');                                                                                                     // 测试没有session条件
r($taskTester->reportConditionTest($sql)) && p() && e("id in (execution  = '4' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0')"); // 测试有session条件
