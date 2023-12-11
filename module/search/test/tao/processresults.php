#!/usr/bin/env php
<?php

/**

title=测试 searchModel->processResultsTest();
timeout=0
cid=1

- 测试 andOr,operator,value 的值
 - 第0条的title属性 @<span class='text-danger'>aaa </span>
 - 第0条的summary属性 @<span class='text-danger'>aaa </span>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

$results = array();

$record = new stdClass();
$record->objectType = 'bug';
$record->objectID   = 8;
$record->title      = 'aaa__';
$record->content    = 'aaa__';

$results[] = $record;

$objectList = array();

$words = 'aaa__';

$search = new searchTest();
r($search->processResultsTest($results, $objectList, $words)) && p('0:title,summary') && e("<span class='text-danger'>aaa </span>,<span class='text-danger'>aaa </span>"); //测试处理的搜索结果的值
