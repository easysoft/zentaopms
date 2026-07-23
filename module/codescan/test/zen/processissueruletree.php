#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processIssueRuleTree();
timeout=0
cid=0

- 测试空参数返回数组 >> 1
- 测试带ruleTree返回数组 >> 1
- 测试带params返回数组 >> 1
- 测试空参数再次返回数组 >> 1
- 测试带taskID返回数组 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_array($test->processIssueRuleTreeTest(array(), '', array()))) && p() && e('1');
$ruleTree = array((object)array('name' => 'root', 'ref' => '', 'children' => array()));
r(is_array($test->processIssueRuleTreeTest($ruleTree, 'url/%s', array()))) && p() && e('1');
$ruleTree2 = array((object)array('name' => 'PHPStan', 'ref' => '1', 'children' => array()));
r(is_array($test->processIssueRuleTreeTest($ruleTree2, 'url', array('repoID' => 1)))) && p() && e('1');
r(is_array($test->processIssueRuleTreeTest(array(), 'url', array()))) && p() && e('1');
r(is_array($test->processIssueRuleTreeTest(array(), '', array('taskID' => 5)))) && p() && e('1');
