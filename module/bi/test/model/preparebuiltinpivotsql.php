#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinPivotSQLTest();
timeout=0
cid=1

- 测试透视表插入数量 @140
- 测试第1张透视表信息
 - 属性id @1000
 - 属性version @1
 - 属性code @finishedProjectDuration
- 测试第3张透视表信息
 - 属性id @1002
 - 属性version @1
 - 属性code @productBugSummary
- 测最后一张透视表信息
 - 属性id @1025
 - 属性version @1.1
 - 属性code @slovedBugsroot

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

$bi = new biTest();

r(count($bi->prepareBuiltinPivotSQLTest('insert'))) && p('') && e('140'); //测试透视表插入数量

$pivotSqls = $bi->prepareBuiltinPivotSQLTest('insert');

r($pivotSqls[0]) && p('id,version,code') && e('1000,1,finishedProjectDuration');   //测试第1张透视表信息
r($pivotSqls[2]) && p('id,version,code') && e('1002,1,productBugSummary');         //测试第3张透视表信息
r(end($pivotSqls)) && p('id,version,code') && e('1025,1.1,slovedBugs');            //测最后一张透视表信息