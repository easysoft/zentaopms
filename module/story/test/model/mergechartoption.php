#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

/**

title=测试 storyModel->mergeChartOption();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('story');

$tester->story->mergeChartOption('storiesPerProduct');
r($tester->story->lang->story->report->storiesPerProduct) && p('type,width,height') && e('pie,500,140');

$tester->story->mergeChartOption('storiesPerModule');
r($tester->story->lang->story->report->storiesPerModule->graph) && p('xAxisName') && e('模块');

$tester->story->mergeChartOption('storiesPerSource');
r($tester->story->lang->story->report->storiesPerSource->graph) && p('xAxisName') && e('来源');

$tester->story->mergeChartOption('storiesPerPlan');
r($tester->story->lang->story->report->storiesPerPlan->graph) && p('xAxisName') && e('计划');

$tester->story->mergeChartOption('storiesPerStatus');
r($tester->story->lang->story->report->storiesPerStatus->graph) && p('xAxisName') && e('状态');

$tester->story->mergeChartOption('storiesPerStage');
r($tester->story->lang->story->report->storiesPerStage->graph) && p('xAxisName') && e('所处阶段');

$tester->story->mergeChartOption('storiesPerPri');
r($tester->story->lang->story->report->storiesPerPri->graph) && p('xAxisName') && e('优先级');

$tester->story->mergeChartOption('storiesPerEstimate');
r($tester->story->lang->story->report->storiesPerEstimate->graph) && p('xAxisName') && e('预计时间');

$tester->story->mergeChartOption('storiesPerOpenedBy');
r($tester->story->lang->story->report->storiesPerOpenedBy->graph) && p('xAxisName') && e('由谁创建');

$tester->story->mergeChartOption('storiesPerAssignedTo');
r($tester->story->lang->story->report->storiesPerAssignedTo->graph) && p('xAxisName') && e('当前指派');

$tester->story->mergeChartOption('storiesPerClosedReason');
r($tester->story->lang->story->report->storiesPerClosedReason->graph) && p('xAxisName') && e('关闭原因');

$tester->story->mergeChartOption('storiesPerChange');
r($tester->story->lang->story->report->storiesPerChange->graph) && p('xAxisName') && e('变更次数');
