#!/usr/bin/env php
<?php

/**

title=测试 holidayModel::isWorkingDay();
timeout=0
cid=16748

- 测试工作日期间的日期（春节补班） @It is a working day
- 测试工作日期间的日期（劳动节补班） @It is a working day
- 测试工作日期间的日期（劳动节补班2） @It is a working day
- 测试工作日期间的日期（端午节补班） @It is a working day
- 测试非工作日期间的日期 @It is not a working day
- 测试空字符串日期 @It is not a working day
- 测试工作日期间的日期（国庆节补班） @It is a working day

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('holiday')->loadYaml('holiday')->gen(10);

su('admin');

$holiday = new holidayModelTest();

r($holiday->isWorkingDayTest('2023-01-28')) && p() && e('It is a working day');     // 测试工作日期间的日期（春节补班）
r($holiday->isWorkingDayTest('2023-04-23')) && p() && e('It is a working day');     // 测试工作日期间的日期（劳动节补班）
r($holiday->isWorkingDayTest('2023-05-06')) && p() && e('It is a working day');     // 测试工作日期间的日期（劳动节补班2）
r($holiday->isWorkingDayTest('2023-06-25')) && p() && e('It is a working day');     // 测试工作日期间的日期（端午节补班）
r($holiday->isWorkingDayTest('2024-12-25')) && p() && e('It is not a working day'); // 测试非工作日期间的日期
r($holiday->isWorkingDayTest('')) && p() && e('It is not a working day');           // 测试空字符串日期
r($holiday->isWorkingDayTest('2023-10-07')) && p() && e('It is a working day');     // 测试工作日期间的日期（国庆节补班）