#!/usr/bin/env php
<?php

/**

title=getCalcRoot
timeout=0
cid=1

- 执行metric模块的getCalcRootTest方法  @module/metric/calc/

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getCalcRootTest()) && p() && e('module/metric/calc/');