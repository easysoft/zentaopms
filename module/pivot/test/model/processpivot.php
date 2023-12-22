#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->processPivot();
timeout=0
cid=1

- 测试传入数组的情况下返回是否是数组 @1
- 测试传入对象的情况下返回是否是对象 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

$pivotIDList = array(1001, 1001, 1002, 1003);
$pivotList   = array();

global $tester;
foreach($pivotIDList as $pivotID) $pivotList[] = $tester->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->fetch();

r(is_array($pivot->processPivot(array($pivotList[0]),false))) && p('') && e(1);   //测试传入数组的情况下返回是否是数组
r(is_object($pivot->processPivot($pivotList[1], true)))       && p('') && e(1);   //测试传入对象的情况下返回是否是对象