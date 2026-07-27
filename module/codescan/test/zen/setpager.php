#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->setPager();
timeout=0
cid=0

- 测试无参调用返回pager对象 >> 1
- 测试带recPerPage和pageID返回pager对象 >> 1
- 测试不同分页参数返回pager对象 >> 1
- 测试较大分页返回pager对象 >> 1
- 测试较大页码返回pager对象 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_object($test->setPagerTest())) && p() && e('1');
r(is_object($test->setPagerTest(20, 1))) && p() && e('1');
r(is_object($test->setPagerTest(10, 2))) && p() && e('1');
r(is_object($test->setPagerTest(50, 1))) && p() && e('1');
r(is_object($test->setPagerTest(20, 5))) && p() && e('1');
