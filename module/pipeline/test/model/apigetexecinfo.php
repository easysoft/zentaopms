#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::apiGetExecInfo();
timeout=0
cid=0

- 测试execID=0边界情况 @1
- 测试execID=999不存在 @1
- 测试execID=负数 @1
- 测试execID=7401正常调用 @1
- 测试execID=大数字 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tester = new pipelineModelTest();

r($tester->apiGetExecInfoTest(0) === false ? '1' : '0') && p() && e('1');
r($tester->apiGetExecInfoTest(999) === false ? '1' : '0') && p() && e('1');
r($tester->apiGetExecInfoTest(-1) === false ? '1' : '0') && p() && e('1');
r($tester->apiGetExecInfoTest(7401) === false ? '1' : '0') && p() && e('1');
r($tester->apiGetExecInfoTest(99999) === false ? '1' : '0') && p() && e('1');
