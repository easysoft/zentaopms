#!/usr/bin/env php
<?php

/**

title=测试 storyModel->mergeChartOption();
cid=18574

- 执行$tester->story->lang->story->report->storiesPerProduct
 - 属性type @pie
 - 属性width @500
 - 属性height @140
- 执行$tester->story->lang->story->report->storiesPerModule->graph属性xAxisName @模块
- 执行$tester->story->lang->story->report->storiesPerSource->graph属性xAxisName @来源
- 执行$tester->story->lang->story->report->storiesPerPlan->graph属性xAxisName @计划
- 执行$tester->story->lang->story->report->storiesPerStatus->graph属性xAxisName @状态
- 执行$tester->story->lang->story->report->storiesPerStage->graph属性xAxisName @所处阶段
- 执行$tester->story->lang->story->report->storiesPerPri->graph属性xAxisName @优先级
- 执行$tester->story->lang->story->report->storiesPerEstimate->graph属性xAxisName @预计时间
- 执行$tester->story->lang->story->report->storiesPerOpenedBy->graph属性xAxisName @由谁创建
- 执行$tester->story->lang->story->report->storiesPerAssignedTo->graph属性xAxisName @当前指派
- 执行$tester->story->lang->story->report->storiesPerClosedReason->graph属性xAxisName @关闭原因
- 执行$tester->story->lang->story->report->storiesPerChange->graph属性xAxisName @变更次数

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

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
