#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php"; su('admin');
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=bugModel->getDataOfOpenedBugsPerDay();
cid=1
pid=1

获取创建的数据 >> 23

*/

$bug=new bugTest();
r($bug->getDataOfOpenedBugsPerDayTest()) && p('0:value') && e('23');   // 获取创建的数据