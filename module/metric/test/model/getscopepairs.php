#!/usr/bin/env php
<?php

/**

title=getScopePairs
timeout=0
cid=1

- 获取全部的scopePair，取第一个第0条的value属性 @program
- 获取全部的scopePair，取第二个第1条的value属性 @project
- 获取部分的scopePair，取第一个第0条的value属性 @project
- 获取部分的scopePair，取第二个第1条的value属性 @product

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r($metric->getScopePairs()) && p('0:value') && e('project');      // 获取全部的scopePair，取第一个
r($metric->getScopePairs()) && p('1:value') && e('product');      // 获取全部的scopePair，取第二个
r($metric->getScopePairs(false)) && p('0:value') && e('project'); // 获取部分的scopePair，取第一个
r($metric->getScopePairs(false)) && p('1:value') && e('product'); // 获取部分的scopePair，取第二个