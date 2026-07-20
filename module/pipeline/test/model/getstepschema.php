#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getStepSchema();
timeout=0
cid=0

- 测试空stepName空字符串 @1
- 测试普通stepName输入 @1
- 测试带版本号stepName结果为空 @1
- 测试长字符串stepName结果为空 @1
- 测试带路径stepName结果为空 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tester = new pipelineModelTest();

r($tester->getStepSchemaTest('') === '' ? '1' : '0') && p() && e('1');
r($tester->getStepSchemaTest('node') === '' ? '1' : '0') && p() && e('1');
r($tester->getStepSchemaTest('run-v1.0') === '' ? '1' : '0') && p() && e('1');
r($tester->getStepSchemaTest(str_repeat('a', 200)) === '' ? '1' : '0') && p() && e('1');
r($tester->getStepSchemaTest('docker-build') === '' ? '1' : '0') && p() && e('1');
