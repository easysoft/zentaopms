#!/usr/bin/env php
<?php

/**

title=processImplementTips
timeout=0
cid=1

- 传入code替换语言项 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->processImplementTips('count_of_bug')) && p('') && e(1); // 传入code替换语言项