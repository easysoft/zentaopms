#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genRadar();
timeout=0
cid=15568

- 执行chartTest模块的genRadarTest方法，参数是'normal')['series']['data']  @1
- 执行chartTest模块的genRadarTest方法，参数是'multi')['series']['data']  @2
- 执行chartTest模块的genRadarTest方法，参数是'empty')['series']['data']  @0
- 执行chartTest模块的genRadarTest方法，参数是'filtered')['radar']['indicator']  @3
- 执行chartTest模块的genRadarTest方法，参数是'multilang')['series']['data'][0]['name']  @计数值(计数)

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');

$chartTest = new chartTest();

r(count($chartTest->genRadarTest('normal')['series']['data'])) && p() && e('1');
r(count($chartTest->genRadarTest('multi')['series']['data'])) && p() && e('2');
r(count($chartTest->genRadarTest('empty')['series']['data'])) && p() && e('0');
r(count($chartTest->genRadarTest('filtered')['radar']['indicator'])) && p() && e('3');
r($chartTest->genRadarTest('multilang')['series']['data'][0]['name']) && p() && e('计数值(计数)');