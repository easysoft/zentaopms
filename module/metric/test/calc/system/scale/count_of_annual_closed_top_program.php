#!/usr/bin/env php
<?php

/**

title=count_of_annual_closed_top_program
timeout=0
cid=1

- 测试2011年关闭的一级项目集数。第0条的value属性 @5
- 测试2012年关闭的一级项目集数。第0条的value属性 @5
- 测试2013年关闭的一级项目集数。第0条的value属性 @4
- 测试2020年关闭的一级项目集数。第0条的value属性 @3
- 测试错误年份关闭的一级项目集数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('program_closed', true, 4)->gen(356, true, false);

$metric = new metricTest();
$calc = $metric->calcMetric(__FILE__);

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('5'); // 测试2011年关闭的一级项目集数。
r($calc->getResult(array('year' => '2012'))) && p('0:value') && e('5'); // 测试2012年关闭的一级项目集数。
r($calc->getResult(array('year' => '2013'))) && p('0:value') && e('4'); // 测试2013年关闭的一级项目集数。
r($calc->getResult(array('year' => '2020'))) && p('0:value') && e('3'); // 测试2020年关闭的一级项目集数。
r($calc->getResult(array('year' => '9999'))) && p('') && e('0');        // 测试错误年份关闭的一级项目集数。