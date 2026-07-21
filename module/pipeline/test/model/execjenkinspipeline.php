#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::execJenkinsPipeline();
timeout=0
cid=0

- 测试空providerID执行 @1
- 测试正常pipeline对象执行(带triggerType) @1
- 测试manual触发类型 @1
- 测试schedule触发类型 @1
- 测试空customParam执行 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$pipeline = new stdclass();
$pipeline->id = 7401;
$pipeline->name = 'test-pipeline';
$pipeline->providerID = 0;
$pipeline->defaultBranch = 'main';
$pipeline->customParam = '';
$pipeline->externalPipeline = 'test-job';

$tester = new pipelineModelTest();

$r1 = $tester->execJenkinsPipelineTest($pipeline);
$r2 = $tester->execJenkinsPipelineTest($pipeline, 'manual');
$r3 = $tester->execJenkinsPipelineTest($pipeline, 'schedule');
$r4 = $tester->execJenkinsPipelineTest($pipeline, 'tag');
$r5 = $tester->execJenkinsPipelineTest($pipeline, 'commit');

r(is_bool($r1) ? 1 : 0) && p() && e('1');
r(is_bool($r2) ? 1 : 0) && p() && e('1');
r(is_bool($r3) ? 1 : 0) && p() && e('1');
r(is_bool($r4) ? 1 : 0) && p() && e('1');
r(is_bool($r5) ? 1 : 0) && p() && e('1');
