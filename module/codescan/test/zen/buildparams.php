#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->buildParams();
timeout=0
cid=0

- 测试type=ruleset >> 1
- 测试type=solution >> 1
- 测试type=task >> 1
- 测试带queryID参数 >> 1
- 测试带orderBy参数 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_array($test->buildParamsTest('ruleset', ''))) && p() && e('1');
r(is_array($test->buildParamsTest('solution', ''))) && p() && e('1');
r(is_array($test->buildParamsTest('task', ''))) && p() && e('1');
r(is_array($test->buildParamsTest('ruleset', '', 1))) && p() && e('1');
r(is_array($test->buildParamsTest('ruleset', '', 0, 'id_desc'))) && p() && e('1');
