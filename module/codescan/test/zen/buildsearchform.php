#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->buildSearchForm();
timeout=0
cid=0

- step1 >> 1
- step2 >> 1
- step3 >> 1
- step4 >> 1
- step5 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_null($test->buildSearchFormTest(array('module' => 'codescan', 'params' => array()), 0, 'url'))) && p() && e('1');
r(is_null($test->buildSearchFormTest(array('module' => 'codescan'), 1, ''))) && p() && e('1');
r(is_null($test->buildSearchFormTest(array('module' => 'codescan'), 0, 'url2'))) && p() && e('1');
r(is_null($test->buildSearchFormTest(array('module' => 'codescan'), 0, 'url3'))) && p() && e('1');
r(is_null($test->buildSearchFormTest(array('module' => 'codescan'), '0', 'url4'))) && p() && e('1');
