#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->commonData();
timeout=0
cid=0

- 测试无参数调用 >> 1
- 测试include='lang' >> 1
- 测试usePair=false >> 1
- 测试include='tag|plugin' >> 1
- 测试再次无参数调用 >> 1

*/

su('admin');
$test = new codescanZenTest();

r($test->commonDataTest()) && p() && e('1');
r($test->commonDataTest('lang')) && p() && e('1');
r($test->commonDataTest('', false)) && p() && e('1');
r($test->commonDataTest('tag|plugin')) && p() && e('1');
r($test->commonDataTest()) && p() && e('1');
