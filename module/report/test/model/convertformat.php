#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->convertFormat();
cid=18160
pid=1

*/
$report = new reportTest();

global $tester;
$execution = $tester->loadModel('execution');

$date1 = $execution->getDateList('2022-01-01', '2022-01-05', 'noweekend');
$date2 = $execution->getDateList('2022-01-06', '2022-01-10', 'noweekend');
$date3 = $execution->getDateList('2022-01-11', '2022-01-15', 'noweekend');
$date4 = $execution->getDateList('2022-01-16', '2022-01-20', 'noweekend');
$date5 = $execution->getDateList('2022-01-01', '2022-01-05', 'withweekend');
$date6 = $execution->getDateList('2022-01-06', '2022-01-10', 'withweekend');
$date7 = $execution->getDateList('2022-01-11', '2022-01-15', 'withweekend');
$date8 = $execution->getDateList('2022-01-16', '2022-01-20', 'withweekend');

$format = array('Y-m-d', 'Ymd');

r($report->convertFormatTest($date1[0], $format[0])) && p() && e('2022-01-03,2022-01-04,2022-01-05');                       // 测试获取 2022-01-01 到 2022-01-05 的日期列表 Y-m-d
r($report->convertFormatTest($date2[0], $format[0])) && p() && e('2022-01-06,2022-01-07,2022-01-10');                       // 测试获取 2022-01-06 到 2022-01-10 的日期列表 Y-m-d
r($report->convertFormatTest($date3[0], $format[0])) && p() && e('2022-01-11,2022-01-12,2022-01-13,2022-01-14');            // 测试获取 2022-01-11 到 2022-01-15 的日期列表 Y-m-d
r($report->convertFormatTest($date4[0], $format[0])) && p() && e('2022-01-17,2022-01-18,2022-01-19,2022-01-20');            // 测试获取 2022-01-16 到 2022-01-20 的日期列表 Y-m-d
r($report->convertFormatTest($date5[0], $format[0])) && p() && e('2022-01-01,2022-01-02,2022-01-03,2022-01-04,2022-01-05'); // 测试获取 2022-01-01 到 2022-01-05 的日期列表 Y-m-d
r($report->convertFormatTest($date6[0], $format[0])) && p() && e('2022-01-06,2022-01-07,2022-01-08,2022-01-09,2022-01-10'); // 测试获取 2022-01-06 到 2022-01-10 的日期列表 Y-m-d
r($report->convertFormatTest($date7[0], $format[0])) && p() && e('2022-01-11,2022-01-12,2022-01-13,2022-01-14,2022-01-15'); // 测试获取 2022-01-11 到 2022-01-15 的日期列表 Y-m-d
r($report->convertFormatTest($date8[0], $format[0])) && p() && e('2022-01-16,2022-01-17,2022-01-18,2022-01-19,2022-01-20'); // 测试获取 2022-01-16 到 2022-01-20 的日期列表 Y-m-d

r($report->convertFormatTest($date1[0], $format[1])) && p() && e('20220103,20220104,20220105');                   // 测试获取 2022-01-01 到 2022-01-05 的日期列表 Ymd
r($report->convertFormatTest($date2[0], $format[1])) && p() && e('20220106,20220107,20220110');                   // 测试获取 2022-01-06 到 2022-01-10 的日期列表 Ymd
r($report->convertFormatTest($date3[0], $format[1])) && p() && e('20220111,20220112,20220113,20220114');          // 测试获取 2022-01-11 到 2022-01-15 的日期列表 Ymd
r($report->convertFormatTest($date4[0], $format[1])) && p() && e('20220117,20220118,20220119,20220120');          // 测试获取 2022-01-16 到 2022-01-20 的日期列表 Ymd
r($report->convertFormatTest($date5[0], $format[1])) && p() && e('20220101,20220102,20220103,20220104,20220105'); // 测试获取 2022-01-01 到 2022-01-05 的日期列表 Ymd
r($report->convertFormatTest($date6[0], $format[1])) && p() && e('20220106,20220107,20220108,20220109,20220110'); // 测试获取 2022-01-06 到 2022-01-10 的日期列表 Ymd
r($report->convertFormatTest($date7[0], $format[1])) && p() && e('20220111,20220112,20220113,20220114,20220115'); // 测试获取 2022-01-11 到 2022-01-15 的日期列表 Ymd
r($report->convertFormatTest($date8[0], $format[1])) && p() && e('20220116,20220117,20220118,20220119,20220120'); // 测试获取 2022-01-16 到 2022-01-20 的日期列表 Ymd
