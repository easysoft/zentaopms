#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::buildSearchForm();
timeout=0
cid=0

- 测试buildSearchForm(空配置) @1
- 测试buildSearchForm(带queryID) @1
- 测试buildSearchForm(带actionURL) @1
- 测试buildSearchForm(完整参数) @1
- 测试buildSearchForm(queryID=0) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineZenTest();

$v1 = $tester->buildSearchFormTest(array(), 0, '');
$v2 = $tester->buildSearchFormTest(array(), 1, '');
$v3 = $tester->buildSearchFormTest(array(), 0, '/pipeline-browse');
$v4 = $tester->buildSearchFormTest(array('module' => 'pipeline'), 0, '');
$v5 = $tester->buildSearchFormTest(array(), 0, '');

$ok = '1';

r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
