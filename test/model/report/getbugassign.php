#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getBugAssign();
cid=1
pid=1

获取指派给 admin 的bug数量 >> 85
获取指派给 dev1 的bug数量 >> 35
获取指派给 test1 的bug数量 >> 5

*/

$report = new reportTest();

r($report->getBugAssignTest()) && p('admin') && e('85'); // 获取指派给 admin 的bug数量
r($report->getBugAssignTest()) && p('dev1')  && e('35'); // 获取指派给 dev1 的bug数量
r($report->getBugAssignTest()) && p('test1') && e('5');  // 获取指派给 test1 的bug数量