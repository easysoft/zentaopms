#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->getYearPairs();
cid=1
pid=1

测试getYearPairsTest方法 >> 1

*/

$holiday = new holidayTest();

r($holiday->getYearPairsTest()) && p() && e('1'); //测试getYearPairsTest方法