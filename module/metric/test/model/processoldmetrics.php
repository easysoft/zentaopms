#!/usr/bin/env php
<?php

/**

title=测试 metricModel::processOldMetrics();
timeout=0
cid=17149

- 执行processOldMetricsOpenTest()[0]模块的isOldMetric方法  @0
- 执行processOldMetricsMaxTest()[0]模块的isOldMetric方法  @1
- 执行metricTest模块的processOldMetricsEmptyTest方法  @0
- 执行processOldMetricsNewTest()[0]模块的isOldMetric方法  @0
- 执行metricTest模块的processOldMetricsMaxTest方法 第0条的unit属性 @个

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('basicmeas')->gen(0);

$metricTest = new metricModelTest();

for($i = 1; $i <= 10; $i++)
{
    $record = new stdClass();
    $record->id          = $i;
    $record->purpose     = 'scale';
    $record->scope       = 'system';
    $record->object      = 'story';
    $record->name        = "基础度量{$i}";
    $record->code        = "metric{$i}";
    $record->unit        = $i == 1 ? '个' : '次';
    $record->configure   = '{"type":"sql","sql":"SELECT 1"}';
    $record->params      = '[]';
    $record->definition  = "定义{$i}";
    $record->source      = 'system';
    $record->collectType = 'cron';
    $record->collectConf = '{}';
    $record->execTime    = '00:00';
    $record->collectedBy = 'system';
    $record->createdBy   = 'admin';
    $record->createdDate = '2026-01-01 00:00:00';
    $record->editedBy    = '';
    $record->editedDate  = null;
    $record->order       = $i;
    $record->deleted     = 0;

    $metricTest->instance->dao->insert(TABLE_BASICMEAS)->data($record)->exec();
}

su('admin');

r((int)$metricTest->processOldMetricsOpenTest()[0]->isOldMetric) && p() && e('0');
r((int)$metricTest->processOldMetricsMaxTest()[0]->isOldMetric)  && p() && e('1');
r(count($metricTest->processOldMetricsEmptyTest())) && p() && e('0');
r((int)$metricTest->processOldMetricsNewTest()[0]->isOldMetric)  && p() && e('0');
r($metricTest->processOldMetricsMaxTest())   && p('0:unit')        && e('个');