#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getAllPivotByGroupID();
timeout=0
cid=0

- 执行pivotTest模块的getAllPivotByGroupID方法，参数是60  @array
- 执行pivotTest模块的getAllPivotByGroupID方法，参数是999  @array
- 执行pivotTest模块的getAllPivotByGroupID方法  @array
- 执行pivotTest模块的getAllPivotByGroupID方法，参数是-1  @array
- 执行pivotTest模块的getAllPivotByGroupID方法，参数是60
 - 第0条的id属性 @1001
 - 第0条的0:group属性 @60

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getAllPivotByGroupID(60)) && p() && e('array');
r($pivotTest->getAllPivotByGroupID(999)) && p() && e('array');
r($pivotTest->getAllPivotByGroupID(0)) && p() && e('array');
r($pivotTest->getAllPivotByGroupID(-1)) && p() && e('array');
r($pivotTest->getAllPivotByGroupID(60)) && p('0:id,0:group') && e('1001,60');