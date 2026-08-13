#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::isClickable();
timeout=0
cid=0

- 测试draft状态exec不可点击 @0
- 测试active状态exec可点击 @1
- 测试empty状态execution不可点击 @0
- 测试active状态执行可点击 @1
- 测试普通action(edit)总是可点击 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tester = new pipelineModelTest();

$draftPipeline   = (object)array('status' => 'draft');
$activePipeline  = (object)array('status' => 'active');
$emptyPipeline   = (object)array('status' => '');
$nullPipeline    = (object)array('status' => null);

r($tester->isClickableTest($draftPipeline, 'exec') ? '1' : '0') && p() && e('0');
r($tester->isClickableTest($activePipeline, 'exec') ? '1' : '0') && p() && e('1');
r($tester->isClickableTest($emptyPipeline, 'execution') ? '1' : '0') && p() && e('0');
r($tester->isClickableTest($activePipeline, 'execution') ? '1' : '0') && p() && e('1');
r($tester->isClickableTest($draftPipeline, 'edit') ? '1' : '0') && p() && e('1');
