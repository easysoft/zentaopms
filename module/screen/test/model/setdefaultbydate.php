#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 screenModel->setFilterSql();
timeout=0
cid=1

- start为空，end为空，不设置默认值 @1
- start不为空，end为空，时间为start的毫秒时间戳。 @1
- start为空，end不为空，时间为end的毫秒时间戳。 @1
- start不为空，end不为空，时间为start和end的毫秒时间戳。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('user')->gen(10);

$screen = new screenTest();

$filters = array();
$filters['default'] = array();
$filters['default']['start'] = '2019-01-01';

$startTimeList = array('', '2023-01-01');
$endTimeList   = array('', '2024-01-01');

function set(&$filters, $start, $end)
{
    $filters = array();
    $filters['default']['begin'] = $start;
    $filters['default']['end']   = $end;
}

set($filters, $startTimeList[0], $endTimeList[0]);
$screen->setDefaultByDateTest($filters);
r(is_null($filters['default'])) && p('') && e(1);  //start为空，end为空，不设置默认值

set($filters, $startTimeList[1], $endTimeList[0]);
$screen->setDefaultByDateTest($filters);
r($filters['default'] === strtotime($startTimeList[1]) * 1000) && p('') && e(1);  //start不为空，end为空，时间为start的毫秒时间戳。

set($filters, $startTimeList[0], $endTimeList[1]);
$screen->setDefaultByDateTest($filters);
r($filters['default'] === strtotime($endTimeList[1]) * 1000) && p('') && e(1);  //start为空，end不为空，时间为end的毫秒时间戳。

set($filters, $startTimeList[1], $endTimeList[1]);
$screen->setDefaultByDateTest($filters);
$check = true;
if($filters['default'][0] !== strtotime($startTimeList[1]) * 1000) $check = false;
if($filters['default'][1] !== strtotime($endTimeList[1]) * 1000) $check = false;
r($check) && p('') && e(1);  //start不为空，end不为空，时间为start和end的毫秒时间戳。