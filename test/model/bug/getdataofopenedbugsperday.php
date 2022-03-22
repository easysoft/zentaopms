#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfOpenedBugsPerDay();
cid=1
pid=1

获取创建的数据 >> 315

*/


$bug=new bugTest();
r($bug->getDataOfOpenedBugsPerDayTest()) && p('0:value') && e('315');   // 获取创建的数据
