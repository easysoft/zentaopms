#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->processPivot();
timeout=0
cid=1

- 测试函数执行以后id为1002的透视表sql是否被修改 @1
- 测试函数执行以后id为1002的透视表返回的setting是否是数组 @1
- 测试函数执行以后id为1002的透视表返回的used是否是true @1
- 测试函数执行以后id为1003的透视表返回的used是否是false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

$pivotIDList = array(1001, 1001, 1002, 1003);
$pivotList   = array();

global $tester;
foreach($pivotIDList as $pivotID) $pivotList[] = $tester->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->fetch();

$add = ';;;;;;;;';
$pivotList[2]->sql .= $add;
$pivot->processPivot($pivotList[2], true);
r(strpos($pivotList[2]->sql, $add) === false) && p('') && e(1);    //测试函数执行以后id为1002的透视表sql是否被修改
r(is_array($pivotList[2]->settings)) && p('') && e(1);             //测试函数执行以后id为1002的透视表返回的setting是否是数组
r($pivotList[2]->used) && p('') && e(1);                           //测试函数执行以后id为1002的透视表返回的used是否是true

$pivot->processPivot($pivotList[3], true);
r($pivotList[3]->used) && p('') && e(0);                           //测试函数执行以后id为1003的透视表返回的used是否是false