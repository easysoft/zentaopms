#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=instanceModel->domainExists();
timeout=0
cid=1

- 查看过滤后的内存选项是否正确 @6
- 查看过滤后的内存选项是否正确 @2

*/

global $tester;
$tester->loadModel('instance');

$resources = new stdclass();
$resources->min = new stdclass();

$resources->min->memory = 1048576 * 1024;
r(count($tester->instance->filterMemOptions($resources))) && p('') && e('6'); // 查看过滤后的内存选项是否正确

$resources->min->memory = 16777216 * 1024;
r(count($tester->instance->filterMemOptions($resources))) && p('') && e('2'); // 查看过滤后的内存选项是否正确