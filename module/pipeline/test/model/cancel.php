#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::cancel();
timeout=0
cid=0

- 取消不存在的执行记录 >> 返回失败 @0
- 执行记录对应的流水线不存在 >> 返回失败并报engine错误 @engine
- 流水线引擎不是gitfox >> 返回失败并报engine错误 @engine
- 执行记录状态不是running或pending >> 返回失败并报status错误 @status
- gitfox接口取消执行失败 >> 返回失败 @0
- gitfox接口取消执行成功 >> 返回成功 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* zendata 生成数据时会往标准输出打印生成过程，先丢弃掉，避免污染用例的输出比对。 */
ob_start();

zenData('user')->gen(2);

/* 8101是gitfox流水线，8102是jenkins流水线。 */
$pipeline = zenData('ops_pipeline');
$pipeline->id->range('8101,8102');
$pipeline->name->range('gitfox流水线,jenkins流水线');
$pipeline->engine->range('gitfox,jenkins');
$pipeline->scope->range('space{2}');
$pipeline->repoID->range('0{2}');
$pipeline->spaceID->range('1{2}');
$pipeline->status->range('active{2}');
$pipeline->createdBy->range('admin{2}');
$pipeline->deleted->range('0{2}');
$pipeline->gen(2);

/* 8204对应的流水线ID不存在。 */
$execution = zenData('ops_pipeline_executions');
$execution->id->range('8201-8204');
$execution->pipelineID->range('8101,8101,8102,9999');
$execution->status->range('running,created,running,running');
$execution->trigger->range('manual{4}');
$execution->createdBy->range('admin{4}');
$execution->gen(4);

ob_end_clean();

su('admin');

$tester = new pipelineModelTest();

r($tester->cancelTest(9999) ? '1' : '0') && p() && e('0');
r($tester->cancelErrorTest(8204)) && p('0') && e('engine');
r($tester->cancelErrorTest(8203)) && p('0') && e('engine');
r($tester->cancelErrorTest(8202)) && p('0') && e('status');
r($tester->cancelTest(8201, false) ? '1' : '0') && p() && e('0');
r($tester->cancelTest(8201) ? '1' : '0') && p() && e('1');
