#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->getFileIssueList();
timeout=0
cid=0

- 测试空参数返回数组 >> 1
- 测试带file参数返回数组 >> 1
- 测试带serviceRepoID返回数组 >> 1
- 测试带file和serviceRepoID返回数组 >> 1
- 测试带taskID返回数组 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_array($test->getFileIssueListTest('', 0, 0))) && p() && e('1');
r(is_array($test->getFileIssueListTest('test.php', 0, 0))) && p() && e('1');
r(is_array($test->getFileIssueListTest('', 1, 0))) && p() && e('1');
r(is_array($test->getFileIssueListTest('test.php', 1, 0))) && p() && e('1');
r(is_array($test->getFileIssueListTest('', 0, 1))) && p() && e('1');
