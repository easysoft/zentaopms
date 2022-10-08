#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserBugs();
cid=1
pid=1

获取人员bug数 >> admin:79;dev1:52;test1:77

*/

$report = new reportTest();

r($report->getUserBugsTest()) && p() && e('admin:79;dev1:52;test1:77'); // 获取人员bug数