#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 todoModel::dateRange();
timeout=0
cid=19253

- 测试 future 类型返回固定日期
 - 属性begin @2030-01-01
 - 属性end @2030-01-01
- 测试数字类型(日期格式)转换为日期范围
 - 属性begin @2024-06-15 00:00:00
 - 属性end @2024-06-15 23:59:59
- 测试 before 类型 begin 为空 @1
- 测试 before 类型 end 返回日期格式 @1
- 测试 today 类型返回日期格式 @1
- 测试 yesterday 类型返回日期格式 @1
- 测试 thisweek 类型 begin 包含时间部分 @1
- 测试 lastweek 类型 begin 包含时间部分 @1
- 测试 thismonth 类型 begin 包含时间部分 @1
- 测试 lastmonth 类型 begin 包含时间部分 @1
- 测试 thisseason 类型 begin 包含时间部分 @1
- 测试 thisyear 类型 begin 包含时间部分 @1
- 测试无效类型返回空范围 @1

*/

$todo = new todoModelTest();

global $tester;
$tester->app->loadClass('date', true);

$today     = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

r($todo->dateRangeTest('future')) && p('begin,end') && e('2030-01-01,2030-01-01'); // 测试 future 类型返回固定日期
r($todo->dateRangeTest('20240615')) && p('begin,end') && e('2024-06-15 00:00:00,2024-06-15 23:59:59'); // 测试数字类型(日期格式)转换为日期范围
r($todo->dateRangeTest('before')['begin'] == '') && p() && e('1'); // 测试 before 类型 begin 为空
r(strlen($todo->dateRangeTest('before')['end']) == 10) && p() && e('1'); // 测试 before 类型 end 返回日期格式
r(strlen($todo->dateRangeTest('today')['begin']) == 10) && p() && e('1'); // 测试 today 类型返回日期格式
r(strlen($todo->dateRangeTest('yesterday')['begin']) == 10) && p() && e('1'); // 测试 yesterday 类型返回日期格式
r(strpos($todo->dateRangeTest('thisweek')['begin'], ' 00:00:00') !== false) && p() && e('1'); // 测试 thisweek 类型 begin 包含时间部分
r(strpos($todo->dateRangeTest('lastweek')['begin'], ' 00:00:00') !== false) && p() && e('1'); // 测试 lastweek 类型 begin 包含时间部分
r(strpos($todo->dateRangeTest('thismonth')['begin'], ' 00:00:00') !== false) && p() && e('1'); // 测试 thismonth 类型 begin 包含时间部分
r(strpos($todo->dateRangeTest('lastmonth')['begin'], ' 00:00:00') !== false) && p() && e('1'); // 测试 lastmonth 类型 begin 包含时间部分
r(strpos($todo->dateRangeTest('thisseason')['begin'], ' 00:00:00') !== false) && p() && e('1'); // 测试 thisseason 类型 begin 包含时间部分
r(strpos($todo->dateRangeTest('thisyear')['begin'], ' 00:00:00') !== false) && p() && e('1'); // 测试 thisyear 类型 begin 包含时间部分
r($todo->dateRangeTest('invalid')['begin'] == '' && $todo->dateRangeTest('invalid')['end'] == '') && p() && e('1'); // 测试无效类型返回空范围