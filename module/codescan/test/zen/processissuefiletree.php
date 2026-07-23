#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processIssueFileTree();
timeout=0
cid=0

- 测试空参数返回数组 >> 1
- 测试带fileTree返回数组 >> 1
- 测试带params返回数组 >> 1
- 测试空参数再次返回数组 >> 1
- 测试带taskID返回数组 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_array($test->processIssueFileTreeTest(array(), '', array()))) && p() && e('1');
$fileTree = array((object)array('name' => 'src', 'path' => 'src/main', 'children' => array()));
r(is_array($test->processIssueFileTreeTest($fileTree, 'url/%s', array()))) && p() && e('1');
$file2 = (object)array('name' => 'app', 'path' => 'app/index', 'children' => array());
r(is_array($test->processIssueFileTreeTest(array($file2), 'url', array('repoID' => 1)))) && p() && e('1');
r(is_array($test->processIssueFileTreeTest(array(), 'url', array()))) && p() && e('1');
r(is_array($test->processIssueFileTreeTest(array(), '', array('taskID' => 5)))) && p() && e('1');
