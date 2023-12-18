#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

/**

title=getScopePairs
cid=1
pid=1

*/

r($metric->getScopePairs()) && p('0:value') && e('system');       // 获取全部的scopePair，取第一个
r($metric->getScopePairs()) && p('1:value') && e('program');      // 获取全部的scopePair，取第二个
r($metric->getScopePairs(false)) && p('0:value') && e('system');  // 获取部分的scopePair，取第一个
r($metric->getScopePairs(false)) && p('1:value') && e('product'); // 获取部分的scopePair，取第二个
