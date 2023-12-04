#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 holidayModel->getYearPairs();
cid=1
pid=1

测试getYearPairsTest方法 >> 1

*/

$holiday = new holidayTest();

r($holiday->getYearPairsTest()) && p() && e('1'); //测试getYearPairsTest方法
