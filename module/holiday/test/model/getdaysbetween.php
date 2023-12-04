#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->getDaysBetween();
cid=1
pid=1

返回2022-04-01到2022-04-21之间的天数 >> 21
返回2022-05-23到2022-05-30之间的天数 >> 8
测试传入一间隔一整年的两天 >> 366
返回2022-05-07到2022-05-08之间的天数 >> 2
测试传入同一天的情况 >> 1

*/

$holiday = new holidayTest();

r($holiday->getDaysBetweenTest('2022-04-01', '2022-04-21')) && p() && e('21');  //返回2022-04-01到2022-04-21之间的天数
r($holiday->getDaysBetweenTest('2022-05-23', '2022-05-30')) && p() && e('8');   //返回2022-05-23到2022-05-30之间的天数
r($holiday->getDaysBetweenTest('2021-01-01', '2022-01-01')) && p() && e('366'); //测试传入一间隔一整年的两天
r($holiday->getDaysBetweenTest('2022-05-07', '2022-05-08')) && p() && e('2');   //返回2022-05-07到2022-05-08之间的天数
r($holiday->getDaysBetweenTest('2022-05-07', '2022-05-07')) && p() && e('1');   //测试传入同一天的情况
