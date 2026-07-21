#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::getJenkinsPipelineList();
timeout=0
cid=0

- 测试getJenkinsPipelineList(providerID=0) @1
- 测试getJenkinsPipelineList(providerID=1) @1
- 测试getJenkinsPipelineList(providerID=999) @1
- 测试getJenkinsPipelineList(repoID=0) @1
- 测试getJenkinsPipelineList(repoID=1) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineZenTest();

$v1 = $tester->getJenkinsPipelineListTest(0);
$v2 = $tester->getJenkinsPipelineListTest(1);
$v3 = $tester->getJenkinsPipelineListTest(999);
$v4 = $tester->getJenkinsPipelineListTest(0, 0);
$v5 = $tester->getJenkinsPipelineListTest(0, 1);

r(is_array($v1)) && p() && e('1');
r(is_array($v2)) && p() && e('1');
r(is_array($v3)) && p() && e('1');
r(is_array($v4)) && p() && e('1');
r(is_array($v5)) && p() && e('1');
