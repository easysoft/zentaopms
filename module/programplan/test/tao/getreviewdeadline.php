#!/usr/bin/env php
<?php

/**

title=测试 loadModel->getReviewDeadline()
cid=0

- 传入空数据 @0
- 获取五个工作日前的日期 @2023-12-22
- 获取六个工作日前的日期 @2023-12-21
- 获取零个工作日前的日期 @2023-12-29

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('programplan');

r($tester->programplan->getReviewDeadline(''))              && p() && e('0');          //传入空数据
r($tester->programplan->getReviewDeadline('2023-12-29'))    && p() && e('2023-12-22'); //获取五个工作日前的日期
r($tester->programplan->getReviewDeadline('2023-12-29', 6)) && p() && e('2023-12-21'); //获取六个工作日前的日期
r($tester->programplan->getReviewDeadline('2023-12-29', 0)) && p() && e('2023-12-29'); //获取零个工作日前的日期
