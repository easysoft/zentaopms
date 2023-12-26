#!/usr/bin/env php
<?php

/**

title=replaceCRLF
timeout=0
cid=1

- 替换str1的换行符 @hello R Zentao ZentaoPHP R R Rhello world.
- 替换str1的换行符 @hello ~ Zentao ZentaoPHP ~ ~ ~hello world.
- 替换str2的换行符 @hello \n\r

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$str1 = "hello \n\r Zentao ZentaoPHP \n \r \r\nhello world.";
$str2 = 'hello \n\r';
r($metric->replaceCRLF($str1, 'R')) && p() && e('hello R Zentao ZentaoPHP R R Rhello world.'); // 替换str1的换行符
r($metric->replaceCRLF($str1, '~')) && p() && e('hello ~ Zentao ZentaoPHP ~ ~ ~hello world.'); // 替换str1的换行符
r($metric->replaceCRLF($str2, '~')) && p() && e('hello \n\r');                                 // 替换str2的换行符