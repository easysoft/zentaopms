#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->getYearPairs();
cid=1
pid=1

测试getYearPairsTest方法 >> 1

*/

$holiday = new holidayTest();

r($holiday->getYearPairsTest()) && p() && e('1'); //测试getYearPairsTest方法