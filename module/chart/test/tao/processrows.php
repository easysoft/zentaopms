#!/usr/bin/env php
<?php
/**

title=测试 chartModel::processRows();
timeout=0
cid=1

- 测试2022年1月份的需求数属性2022-01 @5
- 测试2022年2月份的需求数属性2022-02 @5
- 测试2022年3月份的需求数属性2022-03 @5
- 测试2022年4月份的需求数属性2022-04 @5
- 测试2022年5月份的需求数属性2022-05 @5
- 测试2023年1月份的需求数属性2023-01 @5
- 测试2023年2月份的需求数属性2023-02 @5
- 测试2023年3月份的需求数属性2023-03 @5
- 测试2024年1月份的需求数属性2024-01 @5
- 测试2024年2月份的需求数属性2024-02 @5
- 测试2022年的需求数属性2022 @25
- 测试2023年的需求数属性2023 @15
- 测试2024年的需求数属性2024 @10
- 测试2021年第52周的需求数属性2021年第52周 @1
- 测试2022年第01周的需求数属性2022年第01周 @4
- 测试2022年第05周的需求数属性2022年第05周 @5
- 测试2022年第09周的需求数属性2022年第09周 @5
- 测试2022年第13周的需求数属性2022年第13周 @5
- 测试2022年第18周的需求数属性2022年第18周 @5
- 测试2023年第01周的需求数属性2023年第01周 @5
- 测试2023年第05周的需求数属性2023年第05周 @5
- 测试2023年第09周的需求数属性2023年第09周 @5
- 测试2022-01-01的需求数属性2022-01-01 @1
- 测试2022-01-02的需求数属性2022-01-02 @1
- 测试2022-01-03的需求数属性2022-01-03 @1
- 测试2022-01-04的需求数属性2022-01-04 @1
- 测试2022-01-05的需求数属性2022-01-05 @1
- 测试2022-02-01的需求数属性2022-02-01 @5
- 测试2022-03-01的需求数属性2022-03-01 @5
- 测试2022-04-01的需求数属性2022-04-01 @5
- 测试2022-05-01的需求数属性2022-05-01 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/chart.class.php';

zdTable('story')->config('story')->gen(50);
zdTable('user')->gen(5);
su('admin');

$date = array();
$date['MONTH'] = 'MONTH';
$date['YEAR']  = 'YEAR';
$date['WEEK']  = 'YEARWEEK';
$date['DATE']  = 'DATE';

$chart = new chartTest();
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2022-01') && e('5'); //测试2022年1月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2022-02') && e('5'); //测试2022年2月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2022-03') && e('5'); //测试2022年3月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2022-04') && e('5'); //测试2022年4月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2022-05') && e('5'); //测试2022年5月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2023-01') && e('5'); //测试2023年1月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2023-02') && e('5'); //测试2023年2月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2023-03') && e('5'); //测试2023年3月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2024-01') && e('5'); //测试2024年1月份的需求数
r($chart->processRowsTest($date['MONTH'], 'openedDate', 'id')) && p('2024-02') && e('5'); //测试2024年2月份的需求数

r($chart->processRowsTest($date['YEAR'], 'openedDate', 'id')) && p('2022') && e('25'); //测试2022年的需求数
r($chart->processRowsTest($date['YEAR'], 'openedDate', 'id')) && p('2023') && e('15'); //测试2023年的需求数
r($chart->processRowsTest($date['YEAR'], 'openedDate', 'id')) && p('2024') && e('10'); //测试2024年的需求数

r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2021年第52周') && e('1'); //测试2021年第52周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2022年第01周') && e('4'); //测试2022年第01周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2022年第05周') && e('5'); //测试2022年第05周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2022年第09周') && e('5'); //测试2022年第09周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2022年第13周') && e('5'); //测试2022年第13周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2022年第18周') && e('5'); //测试2022年第18周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2023年第01周') && e('5'); //测试2023年第01周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2023年第05周') && e('5'); //测试2023年第05周的需求数
r($chart->processRowsTest($date['WEEK'], 'openedDate', 'id')) && p('2023年第09周') && e('5'); //测试2023年第09周的需求数

r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-01-01') && e('1'); //测试2022-01-01的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-01-02') && e('1'); //测试2022-01-02的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-01-03') && e('1'); //测试2022-01-03的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-01-04') && e('1'); //测试2022-01-04的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-01-05') && e('1'); //测试2022-01-05的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-02-01') && e('5'); //测试2022-02-01的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-03-01') && e('5'); //测试2022-03-01的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-04-01') && e('5'); //测试2022-04-01的需求数
r($chart->processRowsTest($date['DATE'], 'openedDate', 'id')) && p('2022-05-01') && e('5'); //测试2022-05-01的需求数
