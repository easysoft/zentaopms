#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::isClickable();
timeout=0
cid=0

- 执行tester模块的isClickableTest方法，参数是$draftPipeline, 'exec') ? '1' : '0  @0
- 执行tester模块的isClickableTest方法，参数是$activePipeline, 'exec') ? '1' : '0  @1
- 执行tester模块的isClickableTest方法，参数是$emptyPipeline, 'execution') ? '1' : '0  @0
- 执行tester模块的isClickableTest方法，参数是$activePipeline, 'execution') ? '1' : '0  @1
- 执行tester模块的isClickableTest方法，参数是$draftPipeline, 'edit') ? '1' : '0  @1

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