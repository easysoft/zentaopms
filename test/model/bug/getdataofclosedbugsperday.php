#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfClosedBugsPerDay();
cid=1
pid=1

获取关闭bug的数据 >> 0

*/

$bug=new bugTest();
r($bug->getDataOfClosedBugsPerDayTest()) && p() && e('0');   // 获取关闭bug的数据