#!/usr/bin/env php
<?php
/**
title=parseSqlFunction
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$func1 = 'CREATE FUNCTION `function1`(';
$func2 = 'create function `function2`(';
$func3 = 'create function `function3`';
r($metric->parseSqlFunction($func1)) && p() && e('function1');
r($metric->parseSqlFunction($func2)) && p() && e('function2');
r($metric->parseSqlFunction($func3)) && p() && e('0');
