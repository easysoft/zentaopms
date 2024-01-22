#!/usr/bin/env php
<?php

/**

title=parseSqlFunction
timeout=0
cid=1

- 执行metric模块的parseSqlFunction方法，参数是$func1  @function1
- 执行metric模块的parseSqlFunction方法，参数是$func2  @function2
- 执行metric模块的parseSqlFunction方法，参数是$func3  @0

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