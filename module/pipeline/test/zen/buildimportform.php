#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::buildImportForm();
timeout=0
cid=0

- 测试buildImportForm @1
- 测试buildImportForm(providerID=0) @1
- 测试buildImportForm(repoID=1) @1
- 测试buildImportForm(完整参数) @1
- 测试buildImportForm(repoID=999) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineZenTest();

$v1 = $tester->buildImportFormTest(0);
$v2 = $tester->buildImportFormTest(0, 0);
$v3 = $tester->buildImportFormTest(1);
$v4 = $tester->buildImportFormTest(1, 1);
$v5 = $tester->buildImportFormTest(999);

r(is_array($v1)) && p() && e('1');
r(is_array($v2)) && p() && e('1');
r(is_array($v3)) && p() && e('1');
r(is_array($v4)) && p() && e('1');
r(is_array($v5)) && p() && e('1');
