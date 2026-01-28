#!/usr/bin/env php
<?php
/**

title=测试 buildModel->processBuildForUpdate();
timeout=0
cid=15505

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('build')->loadYaml('build')->gen(20);
zenData('story')->loadYaml('story')->gen(20);
su('admin');

$buildID = array(
    'noExecution' => 9,
    'noStory'     => 3,
    'all'         => 2,
);

$type = array('noBranch', 'noBuild');

$buildTester = new buildModelTest();

r($buildTester->processBuildForUpdate($buildID['noExecution'], $type[0])) && p('branch', '|') && e(',1,2');  // 没有执行的版本
r($buildTester->processBuildForUpdate($buildID['noExecution'], $type[1])) && p('branch', '|') && e('1');  // 没有执行的版本

r($buildTester->processBuildForUpdate($buildID['noStory'], $type[0])) && p('branch', '|') && e('~~');  // 没有关联需求的版本
r($buildTester->processBuildForUpdate($buildID['noStory'], $type[1])) && p('branch', '|') && e('1');  // 没有关联需求的版本

r($buildTester->processBuildForUpdate($buildID['all'], $type[0])) && p('branch', '|') && e('~~');  // 所有版本
r($buildTester->processBuildForUpdate($buildID['all'], $type[1])) && p('branch', '|') && e('1');  // 所有版本
