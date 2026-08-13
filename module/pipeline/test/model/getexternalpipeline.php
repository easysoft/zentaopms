#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getExternalPipeline();
timeout=0
cid=0

- 测试空statusList查询 @1
- 测试带statusList筛选 @1
- 测试空结果集 @1
- 测试指定created状态 @1
- 测试返回数组类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

r(is_array($tester->getExternalPipelineTest())) && p() && e('1');
r(is_array($tester->getExternalPipelineTest(array('created')))) && p() && e('1');
r(is_array($tester->getExternalPipelineTest(array()))) && p() && e('1');
r(is_array($tester->getExternalPipelineTest(array('created', 'pending')))) && p() && e('1');
r(is_array($tester->getExternalPipelineTest(array('running')))) && p() && e('1');
