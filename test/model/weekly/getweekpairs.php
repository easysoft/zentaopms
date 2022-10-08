#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getWeekPairs();
cid=1
pid=1

 >> 0
 >> 0
 >> 0
 >> 0
 >> 第 1 周( 2022-04-25 ~ 2022-05-01)
 >> 0
 >> 0
 >> 0
 >> 0
 >> 第 1 周( 2022-05-03 ~ 2022-05-09)
 >> 第 4 周( 2022-05-23 ~ 2022-05-29)
 >> 第 239 周( 2026-11-23 ~ 2026-11-29)
 >> 第 5 周( 2022-05-30 ~ 2022-06-05)

*/
$begin = array(1, 2, 3, '');
$end   = array(1, 2, 3, '');

$weekly = new weeklyTest();

r($weekly->getWeekPairsTest($begin[0], $end[0])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[0], $end[1])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[0], $end[2])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[0], $end[3])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[1], $end[0])) && p() && e();
r($weekly->getWeekPairsTest($begin[1], $end[1])) && p() && e();
r($weekly->getWeekPairsTest($begin[1], $end[2])) && p() && e();
r($weekly->getWeekPairsTest($begin[1], $end[3])) && p('20220425') && e('第 1 周( 2022-04-25 ~ 2022-05-01)');
r($weekly->getWeekPairsTest($begin[2], $end[0])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[2], $end[1])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[2], $end[2])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[2], $end[3])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[3], $end[0])) && p('20220503') && e('第 1 周( 2022-05-03 ~ 2022-05-09)');
r($weekly->getWeekPairsTest($begin[3], $end[1])) && p('20220523') && e('第 4 周( 2022-05-23 ~ 2022-05-29)');
r($weekly->getWeekPairsTest($begin[3], $end[2])) && p('20261123') && e('第 239 周( 2026-11-23 ~ 2026-11-29)');
r($weekly->getWeekPairsTest($begin[3], $end[3])) && p('20220530') && e('第 5 周( 2022-05-30 ~ 2022-06-05)');