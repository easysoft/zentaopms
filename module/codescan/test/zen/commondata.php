#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1);

su('admin');

/**

title=测试 codescanZen->commonData();
timeout=0
cid=0

- 测试无参数调用不报错 >> 0
- 测试include='lang'不报错 >> 0
- 测试usePair=false不报错 >> 0
- 测试include='tag|plugin'不报错 >> 0
- 测试再次无参数调用不报错 >> 0

*/

$test = new codescanZenTest();

r(is_null($test->commonDataTest())) && p() && e('1');
r(is_null($test->commonDataTest('lang'))) && p() && e('1');
r(is_null($test->commonDataTest('', false))) && p() && e('1');
r(is_null($test->commonDataTest('tag|plugin'))) && p() && e('1');
r(is_null($test->commonDataTest())) && p() && e('1');
