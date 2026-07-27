#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processIssueData();
timeout=0
cid=0

- 校验content字段 >> test message content
- 校验file字段 >> /src/main.php
- 校验priority字段 >> high
- 测试空对象返回对象 >> 1
- 测试有效issue返回对象 >> 1

*/

su('admin');
$test = new codescanZenTest();

$issue = new stdclass();
$issue->message = 'test message content';
$issue->path = '/src/main.php';
$issue->rulePriority = 'high';
$issue->ruleType = 'bug';
r($test->processIssueDataTest($issue)) && p('content') && e('test message content');
r($test->processIssueDataTest($issue)) && p('file') && e('/src/main.php');
r($test->processIssueDataTest($issue)) && p('priority') && e('high');
r(is_object($test->processIssueDataTest(new stdclass()))) && p() && e('1');
r(is_object($test->processIssueDataTest($issue))) && p() && e('1');
