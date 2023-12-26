#!/usr/bin/env php
<?php

/**

title=isFirstGenerate
timeout=0
cid=1

- 第一次获取，此时表里有数据 @0
- 第二次获取，经过第一次表已经被清空 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
zdTable('metriclib')->config('metriclib_system_product', true)->gen(80);

$metric = new metricTest();

r($metric->isFirstGenerate()) && p() && e('0'); // 第一次获取，此时表里有数据
r($metric->isFirstGenerate()) && p() && e('1'); // 第二次获取，经过第一次表已经被清空