#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::checkRepoEmpty();
timeout=0
cid=0

- 测试checkRepoEmpty @1
- 测试checkRepoEmpty无repo @1
- 测试checkRepoEmpty不报错 @1
- 测试checkRepoEmpty多次 @1
- 测试checkRepoEmpty验证 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineZenTest();

$ok = '1';
$tester->checkRepoEmptyTest();
$tester->checkRepoEmptyTest();
$tester->checkRepoEmptyTest();
$tester->checkRepoEmptyTest();
$tester->checkRepoEmptyTest();

r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
