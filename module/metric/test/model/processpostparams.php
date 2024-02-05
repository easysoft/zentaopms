#!/usr/bin/env php
<?php

/**

title=processPostParams
timeout=0
cid=1

- 传入varName1和queryValue1
 - 属性var1 @query1
 - 属性var2 @query2
 - 属性var3 @query3
- 传入varName2和queryValue2
 - 属性var1 @~~
 - 属性var2 @query2
 - 属性var3 @query3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$varName1 = array('var1', 'var2', 'var3');
$varName2 = array('', 'var2', 'var3');

$queryValue1 = array('query1', 'query2', 'query3');
$queryValue2 = array('', 'query2', 'query3');
r($metric->processPostParams($varName1, $queryValue1)) && p('var1,var2,var3') && e('query1,query2,query3'); // 传入varName1和queryValue1
r($metric->processPostParams($varName2, $queryValue2)) && p('var1,var2,var3') && e('~~,query2,query3');     // 传入varName2和queryValue2