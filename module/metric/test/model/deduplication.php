#!/usr/bin/env php
<?php

/**

title=测试 metricModel::deduplication();
timeout=0
cid=17073

- 执行metricTest模块的deduplicationTest方法，参数是'count_of_bug'，验证去重前记录数 @10
- 执行metricTest模块的deduplicationTest方法，参数是'count_of_bug'，验证去重后记录数 @9
- 执行metricTest模块的deduplicationTest方法，参数是'count_of_annual_created_project'，验证去重后记录数 @5
- 执行metricTest模块的deduplicationTest方法，参数是'count_of_release_in_product'，验证去重后记录数 @2
- 执行metricTest模块的deduplicationTest方法，参数是'nonexistent_metric_code'，验证返回值 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$metricRows = array(
    array('id' => 1, 'scope' => 'system',  'object' => 'bug',     'code' => 'count_of_bug',                   'dateType' => 'nodate'),
    array('id' => 2, 'scope' => 'system',  'object' => 'project', 'code' => 'count_of_annual_created_project', 'dateType' => 'year'),
    array('id' => 3, 'scope' => 'product', 'object' => 'release', 'code' => 'count_of_release_in_product',    'dateType' => 'nodate')
);

su('admin');

$metricTest = new metricModelTest();

foreach($metricRows as $metricRow)
{
    $record = new stdClass();
    $record->id          = $metricRow['id'];
    $record->purpose     = 'scale';
    $record->scope       = $metricRow['scope'];
    $record->object      = $metricRow['object'];
    $record->stage       = 'released';
    $record->type        = 'php';
    $record->name        = $metricRow['code'];
    $record->alias       = $metricRow['code'];
    $record->code        = $metricRow['code'];
    $record->unit        = 'count';
    $record->dateType    = $metricRow['dateType'];
    $record->collector   = 'system';
    $record->desc        = '';
    $record->definition  = '';
    $record->createdBy   = 'admin';
    $record->createdDate = '2026-01-01 00:00:00';
    $record->builtin     = '1';
    $record->fromID      = 0;
    $record->order       = $metricRow['id'];
    $record->deleted     = 0;

    $metricTest->instance->dao->insert(TABLE_METRIC)->data($record)->exec();
}

$metriclibRows = array();

for($i = 1; $i <= 10; $i++)
{
    $metriclibRows[] = array(
        'id'         => $i,
        'metricID'   => 1,
        'metricCode' => 'count_of_bug',
        'system'     => 1,
        'product'    => 0,
        'year'       => '',
        'month'      => '',
        'week'       => '',
        'day'        => '',
        'value'      => '1',
        'date'       => sprintf('2022-01-%02d 00:00:00', $i == 10 ? 9 : $i),
        'deleted'    => 0
    );
}

for($i = 11; $i <= 20; $i++)
{
    $year = 2012 + (($i - 11) % 5);
    $metriclibRows[] = array(
        'id'         => $i,
        'metricID'   => 2,
        'metricCode' => 'count_of_annual_created_project',
        'system'     => 1,
        'product'    => 0,
        'year'       => (string)$year,
        'month'      => '',
        'week'       => '',
        'day'        => '',
        'value'      => '1',
        'date'       => sprintf('%d-01-01 00:00:00', $year),
        'deleted'    => 0
    );
}

for($i = 21; $i <= 30; $i++)
{
    $product = ($i % 2) ? 1 : 3;
    $metriclibRows[] = array(
        'id'         => $i,
        'metricID'   => 3,
        'metricCode' => 'count_of_release_in_product',
        'system'     => 0,
        'product'    => $product,
        'year'       => '',
        'month'      => '',
        'week'       => '',
        'day'        => '',
        'value'      => '1',
        'date'       => sprintf('2022-02-%02d 00:00:00', $product == 1 ? 1 : 2),
        'deleted'    => 0
    );
}

foreach($metriclibRows as $metriclibRow)
{
    $record = new stdClass();
    foreach($metriclibRow as $field => $value) $record->$field = $value;
    $metricTest->instance->dao->insert(TABLE_METRICLIB)->data($record)->exec();
}

$bugDeduplication     = $metricTest->deduplicationTest('count_of_bug');
$annualDeduplication  = $metricTest->deduplicationTest('count_of_annual_created_project');
$releaseDeduplication = $metricTest->deduplicationTest('count_of_release_in_product');
$missingDeduplication = $metricTest->deduplicationTest('nonexistent_metric_code');

r($bugDeduplication)     && p('beforeCount') && e('10');
r($bugDeduplication)     && p('afterCount')  && e('9');
r($annualDeduplication)  && p('afterCount')  && e('5');
r($releaseDeduplication) && p('afterCount')  && e('2');
r($missingDeduplication) && p('result')      && e('0');
