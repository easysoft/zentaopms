#!/usr/bin/env php
<?php

/**

title=测试 convertModel::formatDate();
timeout=0
cid=15797

- 根据日期生成当前时区的日期。 @2026-04-14
- 根据日期生成当前时区的日期。 @2026-04-15
- 根据日期生成当前时区的日期。 @2026-04-16
- 根据日期生成当前时区的日期。 @2026-04-17
- 根据日期生成当前时区的日期。 @2026-04-19

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$tester->loadModel('convert');

$convertTest = new convertModelTest();

r($convertTest->formatDateTest('2026-04-14 12:21:11')) && p() && e('2026-04-14'); // 根据日期生成当前时区的日期。
r($convertTest->formatDateTest('2026-04-15 13:31:22')) && p() && e('2026-04-15'); // 根据日期生成当前时区的日期。
r($convertTest->formatDateTest('2026-04-16 14:41:33')) && p() && e('2026-04-16'); // 根据日期生成当前时区的日期。
r($convertTest->formatDateTest('2026-04-17 15:51:44')) && p() && e('2026-04-17'); // 根据日期生成当前时区的日期。
r($convertTest->formatDateTest('2026-04-18 16:11:55')) && p() && e('2026-04-19'); // 根据日期生成当前时区的日期。
