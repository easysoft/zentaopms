#!/usr/bin/env php
<?php

/**

title=测试 convertModel::formatDatetime();
timeout=0
cid=15797

- 根据日期生成当前时区的日期。 @2026-04-14 20:21:11
- 根据日期生成当前时区的日期。 @2026-04-15 21:31:22
- 根据日期生成当前时区的日期。 @2026-04-16 22:41:33
- 根据日期生成当前时区的日期。 @2026-04-17 23:51:44
- 根据日期生成当前时区的日期。 @2026-04-19 00:11:55

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$tester->loadModel('convert');

$convertTest = new convertModelTest();

r($convertTest->formatDatetimeTest('2026-04-14 12:21:11')) && p() && e('2026-04-14 20:21:11'); // 根据日期生成当前时区的日期。
r($convertTest->formatDatetimeTest('2026-04-15 13:31:22')) && p() && e('2026-04-15 21:31:22'); // 根据日期生成当前时区的日期。
r($convertTest->formatDatetimeTest('2026-04-16 14:41:33')) && p() && e('2026-04-16 22:41:33'); // 根据日期生成当前时区的日期。
r($convertTest->formatDatetimeTest('2026-04-17 15:51:44')) && p() && e('2026-04-17 23:51:44'); // 根据日期生成当前时区的日期。
r($convertTest->formatDatetimeTest('2026-04-18 16:11:55')) && p() && e('2026-04-19 00:11:55'); // 根据日期生成当前时区的日期。
