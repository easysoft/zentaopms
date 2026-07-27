#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 codescanModel->processIssueSnipe();
timeout=0
cid=0

- 测试完整代码片段行号计算 >> 9,12
- 测试空对象返回对象 >> 1
- 测试不匹配snippet返回0,0 >> 0,0
- 测试单行匹配 >> 1,1
- 测试空参数返回对象 >> 1

*/

su('admin');
$test = new codescanModelTest();

$issue1 = new stdclass();
$issue1->snippetWithContext = "line1\nline2\nline3\nline4";
$issue1->snippet = "line2";
$issue1->rangeStartLine = 10;
$issue1->rangeEndLine = 11;
r($test->processissuesnipeTest($issue1)) && p('snippetStartLine,snippetEndLine') && e('9,13');

$empty = new stdclass();
r(is_object($test->processissuesnipeTest($empty))) && p() && e('1');

$noMatch = new stdclass();
$noMatch->snippetWithContext = "abc\ndef";
$noMatch->snippet = "xyz";
$noMatch->rangeStartLine = 5;
$noMatch->rangeEndLine = 6;
r($test->processissuesnipeTest($noMatch)) && p('snippetStartLine,snippetEndLine') && e('0,0');

$single = new stdclass();
$single->snippetWithContext = "abc";
$single->snippet = "abc";
$single->rangeStartLine = 1;
$single->rangeEndLine = 1;
r($test->processissuesnipeTest($single)) && p('snippetStartLine,snippetEndLine') && e('1,1');

r(is_object($test->processissuesnipeTest())) && p() && e('1');
