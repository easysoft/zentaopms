#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getStepGroups();
timeout=0
cid=0

- 执行$result1 === false ? '1' : '0 @1
- 执行$result2 === false ? '1' : '0 @1
- 执行$result3 === false ? '1' : '0 @1
- 执行$result4 === false ? '1' : '0 @1
- 执行$result5 === false ? '1' : '0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tester = new pipelineModelTest();

$result1 = $tester->getStepGroupsTest();
$result2 = $tester->getStepGroupsTest();
$result3 = $tester->getStepGroupsTest();
$result4 = $tester->getStepGroupsTest();
$result5 = $tester->getStepGroupsTest();

r($result1 === false ? '1' : '0') && p() && e('1');
r($result2 === false ? '1' : '0') && p() && e('1');
r($result3 === false ? '1' : '0') && p() && e('1');
r($result4 === false ? '1' : '0') && p() && e('1');
r($result5 === false ? '1' : '0') && p() && e('1');