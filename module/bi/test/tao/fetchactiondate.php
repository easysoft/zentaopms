#!/usr/bin/env php
<?php

/**

title=测试 biTao::fetchActionDate();
timeout=0
cid=15220

- 正常情况:有多条记录时返回最小和最大日期 >> 2009-01-01 00:00:00,2024-12-31 18:00:00
- 正常情况:验证返回的是对象且包含minDate和maxDate字段 >> 1
- 边界情况:日期为2009-01-01的记录应该被包含 >> 2009-01-01 00:00:00
- 边界情况:早于2009-01-01的记录应该被过滤,最小日期应该大于等于2009-01-01 >> 1
- 边界情况:删除所有符合条件的记录后minDate应该为空 >> ~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

global $tester;
$dao = $tester->dao;

$dao->delete()->from(TABLE_ACTION)->exec();

$dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'bug',
    'objectID' => 1,
    'actor' => 'admin',
    'action' => 'opened',
    'date' => '2010-01-01 10:00:00'
))->exec();

$dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'task',
    'objectID' => 2,
    'actor' => 'user1',
    'action' => 'created',
    'date' => '2024-12-31 18:00:00'
))->exec();

$dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'story',
    'objectID' => 3,
    'actor' => 'user2',
    'action' => 'changed',
    'date' => '2015-06-15 12:30:00'
))->exec();

$dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'product',
    'objectID' => 4,
    'actor' => 'admin',
    'action' => 'edited',
    'date' => '2008-12-31 23:59:59'
))->exec();

$dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'project',
    'objectID' => 5,
    'actor' => 'pm1',
    'action' => 'started',
    'date' => '2009-01-01 00:00:00'
))->exec();

su('admin');

$biTest = new biTest();

r($biTest->fetchActionDateTest()) && p('minDate,maxDate') && e('2009-01-01 00:00:00,2024-12-31 18:00:00');
r(is_object($biTest->fetchActionDateTest()) && isset($biTest->fetchActionDateTest()->minDate)) && p() && e('1');
r($biTest->fetchActionDateTest()) && p('minDate') && e('2009-01-01 00:00:00');
r($biTest->fetchActionDateTest()->minDate >= '2009-01-01') && p() && e('1');

$dao->delete()->from(TABLE_ACTION)->where('date')->ge('2009-01-01')->exec();
r($biTest->fetchActionDateTest()) && p('minDate') && e('~~');