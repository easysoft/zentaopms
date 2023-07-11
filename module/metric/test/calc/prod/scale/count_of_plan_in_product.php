#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('productplan')->gen(55);

$metric = new metricTest();

$calc = $metric->calcMetric('prod', 'scale', 'count_of_plan_in_product');

/**

title=count_of_plan_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                    &&  p('') && e('5'); // 测试产品计划按产品分组数
r($calc->getResult(array('product' => '42')))   &&  p('') && e('2'); // 测试产品42下计划数
r($calc->getResult(array('product' => '49')))   &&  p('') && e('2'); // 测试产品49下计划数
r($calc->getResult(array('product' => '50')))   &&  p('') && e('2'); // 测试产品50下计划数
r($calc->getResult(array('product' => '51')))   &&  p('') && e('2'); // 测试产品51下计划数
r($calc->getResult(array('product' => '53')))   &&  p('') && e('1'); // 测试产品53下计划数
r($calc->getResult(array('product' => '1111'))) &&  p('') && e('0'); // 测试不存在的产品下计划数
