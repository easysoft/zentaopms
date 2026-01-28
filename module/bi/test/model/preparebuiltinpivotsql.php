#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinPivotSQLTest();
timeout=0
cid=15199

- 测试透视表插入数量 @140
- 测试第1张透视表信息 @141
- 测试第3张透视表信息 @0
- 测最后一张透视表信息 @122
- 测最后一张透视表信息插入表 @13

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$bi = new biModelTest();

r(count($bi->prepareBuiltinPivotSQLTest('insert'))) && p('') && e('140'); //测试透视表插入数量

$pivotSqls = $bi->prepareBuiltinPivotSQLTest('insert');

r(strpos($pivotSqls[0], 'finishedProjectDuration')) && p('') && e('141'); //测试第1张透视表信息
r(strpos($pivotSqls[2], 'resolution'))              && p('') && e('0');   //测试第3张透视表信息
r(strpos(end($pivotSqls), 'resolution'))            && p('') && e('122'); //测最后一张透视表信息
r(strpos(end($pivotSqls), 'zt_pivotdrill'))         && p('') && e('13');  //测最后一张透视表信息插入表