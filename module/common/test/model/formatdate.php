#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel->formatDate();
timeout=0
cid=15670

- 查看格式化后的日期 @2023-01-01
- 查看格式化后的日期 @2023-01-01 00:00:00
- 查看格式化后的日期 @2023-01-01 00:00:00
- 查看格式化后的日期 @2023-01-01
- 查看格式化后的日期 @2023-01-01 12:12:12
- 查看格式化后的日期 @2023-01-01 12:12:12

*/

global $tester;
$tester->loadModel('common');

$date1 = $tester->common->formatDate('2023-01-01', 'date');
$date2 = $tester->common->formatDate('2023-01-01', 'datetime');
$date3 = $tester->common->formatDate('2023/01/01', 'datetime');
$date4 = $tester->common->formatDate('2023/01/01');
$date5 = $tester->common->formatDate('2023/01/01 12:12:12');
$date6 = $tester->common->formatDate('2023-01-01 12:12:12');

r($date1) && p() && e('2023-01-01');          // 查看格式化后的日期
r($date2) && p() && e('2023-01-01 00:00:00'); // 查看格式化后的日期
r($date3) && p() && e('2023-01-01 00:00:00'); // 查看格式化后的日期
r($date4) && p() && e('2023-01-01');          // 查看格式化后的日期
r($date5) && p() && e('2023-01-01 12:12:12'); // 查看格式化后的日期
r($date6) && p() && e('2023-01-01 12:12:12'); // 查看格式化后的日期