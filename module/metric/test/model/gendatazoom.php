#!/usr/bin/env php
<?php

/**

title=genDataZoom
timeout=0
cid=1

- 测试数据长度为15的情况第0条的end属性 @66.666666666667
- 测试数据长度为15的情况第0条的end属性 @100
- 测试数据长度为10的情况第0条的end属性 @100
- 测试数据长度为10的情况第0条的end属性 @100
- 测试数据长度为15的情况第0条的end属性 @66.666666666667
- 测试数据长度为15的情况第0条的end属性 @100
- 测试数据长度为10的情况第0条的end属性 @100
- 测试数据长度为10的情况第0条的end属性 @100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

r($metric->genDataZoom(15, 10, 'x')) && p('0:end') && e('66.666666666667'); // 测试数据长度为15的情况
r($metric->genDataZoom(15, 15, 'x')) && p('0:end') && e('100');             // 测试数据长度为15的情况
r($metric->genDataZoom(10, 15, 'x')) && p('0:end') && e('100');             // 测试数据长度为10的情况
r($metric->genDataZoom(10, 10, 'x')) && p('0:end') && e('100');             // 测试数据长度为10的情况

r($metric->genDataZoom(15, 10, 'y')) && p('0:end') && e('66.666666666667'); // 测试数据长度为15的情况
r($metric->genDataZoom(15, 15, 'y')) && p('0:end') && e('100');             // 测试数据长度为15的情况
r($metric->genDataZoom(10, 15, 'y')) && p('0:end') && e('100');             // 测试数据长度为10的情况
r($metric->genDataZoom(20, 20, 'y')) && p('0:end') && e('100');             // 测试数据长度为10的情况