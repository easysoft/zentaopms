#!/usr/bin/env php
<?php

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('story')->loadYaml('story_stage_closedreason', true, 4)->gen(2000);
zendata('product')->loadYaml('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                    && p('')        && e('5');   // 测试分组数
r($calc->getResult(array('product' => '1')))    && p('0:value') && e('310'); // 测试产品1已开发的需求数
r($calc->getResult(array('product' => '3')))    && p('0:value') && e('350'); // 测试产品3已开发的需求数
r($calc->getResult(array('product' => '4')))    && p('0:value') && e('0');   // 测试已删除产品4已开发的需求数
r($calc->getResult(array('product' => '9999'))) && p('')        && e('0');   // 测试不存在的产品下的需求数
