#!/usr/bin/env php
<?php

/**

title=测试 metricModel::replaceCRLF();
timeout=0
cid=17155

- 测试步骤1：使用R替换包含多种换行符的字符串 @hello R Zentao ZentaoPHP R R Rhello world.
- 测试步骤2：使用~替换包含多种换行符的字符串 @hello ~ Zentao ZentaoPHP ~ ~ ~hello world.
- 测试步骤3：测试不包含真实换行符的字符串 @hello \n\r
- 测试步骤4：测试空字符串输入 @0
- 测试步骤5：测试只包含换行符的字符串 @0
- 测试步骤6：测试使用默认分号替换符 @test;line;break;here
- 测试步骤7：测试包含空格和换行符的边界情况 @0
- 测试步骤8：测试不包含换行符的普通文本 @only text without newlines

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

$str1 = "hello \n\r Zentao ZentaoPHP \n \r \r\nhello world.";
$str2 = 'hello \n\r';
$str3 = '';
$str4 = "\n\r\r\n";
$str5 = "  \n  \r  ";
$str7 = "only text without newlines";
$str6 = "test\nline\rbreak\r\nhere";

r($metric->replaceCRLFTest($str1, 'R')) && p() && e('hello R Zentao ZentaoPHP R R Rhello world.'); // 测试步骤1：使用R替换包含多种换行符的字符串
r($metric->replaceCRLFTest($str1, '~')) && p() && e('hello ~ Zentao ZentaoPHP ~ ~ ~hello world.'); // 测试步骤2：使用~替换包含多种换行符的字符串
r($metric->replaceCRLFTest($str2, '~')) && p() && e('hello \n\r');                                 // 测试步骤3：测试不包含真实换行符的字符串
r($metric->replaceCRLFTest($str3, 'X')) && p() && e('0');                                          // 测试步骤4：测试空字符串输入
r($metric->replaceCRLFTest($str4, '*')) && p() && e('0');                                          // 测试步骤5：测试只包含换行符的字符串
r($metric->replaceCRLFTest($str6)) && p() && e('test;line;break;here');                           // 测试步骤6：测试使用默认分号替换符
r($metric->replaceCRLFTest($str5, '|')) && p() && e('0');                                         // 测试步骤7：测试包含空格和换行符的边界情况
r($metric->replaceCRLFTest($str7, '@')) && p() && e('only text without newlines');               // 测试步骤8：测试不包含换行符的普通文本