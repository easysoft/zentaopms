#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getByID();
timeout=0
cid=0

- 测试id为0的边界情况 @0
- 测试id为1获取流水线信息 @1
- 测试id为2获取gitlab引擎流水线 @2
- 测试id为999不存在流水线 @0
- 测试id为负数边界情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTester = new pipelineModelTest();

r($pipelineTester->getByIDTest(0)) && p() && e('0');
r($pipelineTester->getByIDTest(1)) && p('id,name,engine') && e('1,pipeline-1,jenkins');
r($pipelineTester->getByIDTest(2)) && p('id,name,engine') && e('2,pipeline-2,gitlab');
r($pipelineTester->getByIDTest(999)) && p() && e('0');
r($pipelineTester->getByIDTest(-1)) && p() && e('0');
