#!/usr/bin/env php
<?php
/**

title=测试 buildModel->processBuildForUpdate();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(20);
zdTable('story')->config('story')->gen(20);
su('admin');

$buildID = array(
    'noExecution' => 9,
    'noStory'     => 3,
    'all'         => 2,
);

$type = array('noBranch', 'noBuild');

$buildTester = new buildTest();

r($buildTester->processBuildForUpdate($buildID['noExecution'], $type[0])) && p('branch', '|') && e(',1,2');  // 没有执行的版本
r($buildTester->processBuildForUpdate($buildID['noExecution'], $type[1])) && p('branch', '|') && e('1');  // 没有执行的版本

r($buildTester->processBuildForUpdate($buildID['noStory'], $type[0])) && p('branch', '|') && e('~~');  // 没有关联需求的版本
r($buildTester->processBuildForUpdate($buildID['noStory'], $type[1])) && p('branch', '|') && e('1');  // 没有关联需求的版本

r($buildTester->processBuildForUpdate($buildID['all'], $type[0])) && p('branch', '|') && e('~~');  // 所有版本
r($buildTester->processBuildForUpdate($buildID['all'], $type[1])) && p('branch', '|') && e('1');  // 所有版本
