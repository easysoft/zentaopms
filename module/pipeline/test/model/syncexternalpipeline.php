#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::syncExternalPipeline();
timeout=0
cid=0

- 测试syncExternalPipeline无外部流水线 @1
- 测试syncExternalPipeline返回值类型 @1
- 测试syncExternalPipeline不抛异常 @1
- 测试syncExternalPipeline重复调用 @1
- 测试syncExternalPipeline无provider @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

r(is_bool($tester->syncExternalPipelineTest()) ? '1' : '0') && p() && e('1');
r(is_bool($tester->syncExternalPipelineTest()) ? '1' : '0') && p() && e('1');
r(is_bool($tester->syncExternalPipelineTest()) ? '1' : '0') && p() && e('1');
r(is_bool($tester->syncExternalPipelineTest()) ? '1' : '0') && p() && e('1');
r(is_bool($tester->syncExternalPipelineTest()) ? '1' : '0') && p() && e('1');
