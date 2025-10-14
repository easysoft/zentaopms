#!/usr/bin/env php
<?php

/**

title=测试 mrZen::saveMrData();
timeout=0
cid=0

- 执行mrTest模块的saveMrDataTest方法，参数是$testRepo, $singleMR  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$testRepo, $multipleMR  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$testRepo, $emptyMRList  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$testRepo, $incompleteMR  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$testRepo, $specialCharsMR  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

$mr = zenData('mr');
$mr->id->range('1-1000');
$mr->hostID->range('1-5');
$mr->sourceProject->range('1-100');
$mr->sourceBranch->range('branch1,branch2,feature,develop,master');
$mr->targetProject->range('1-100');
$mr->targetBranch->range('main,master,develop');
$mr->title->range('Fix bug,Add feature,Update docs,Refactor code');
$mr->status->range('opened,closed,merged');
$mr->gen(10);

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->serviceHost->range('1-5');
$repo->SCM->range('gitlab,gitea,gogs');
$repo->gen(5);

su('admin');

$mrTest = new mrTest();

$testRepo = new stdclass();
$testRepo->id = 1;
$testRepo->serviceHost = 1;
$testRepo->SCM = 'gitlab';

$singleMR = array(
    (object)array(
        'iid' => 1,
        'source_project_id' => '10',
        'source_branch' => 'feature-branch',
        'target_project_id' => '10',
        'target_branch' => 'master',
        'title' => 'Test merge request',
        'created_at' => '2023-12-01T10:00:00Z',
        'updated_at' => '2023-12-01T11:00:00Z',
        'merge_status' => 'can_be_merged',
        'state' => 'opened'
    )
);

$multipleMR = array(
    (object)array(
        'iid' => 2,
        'source_project_id' => '20',
        'source_branch' => 'bug-fix',
        'target_project_id' => '20',
        'target_branch' => 'develop',
        'title' => 'Fix critical bug',
        'created_at' => '2023-12-01T12:00:00Z',
        'updated_at' => '2023-12-01T13:00:00Z',
        'merge_status' => 'can_be_merged',
        'state' => 'opened'
    ),
    (object)array(
        'iid' => 3,
        'source_project_id' => '30',
        'source_branch' => 'feature-new',
        'target_project_id' => '30',
        'target_branch' => 'main',
        'title' => 'Add new feature',
        'created_at' => '2023-12-01T14:00:00Z',
        'updated_at' => '2023-12-01T15:00:00Z',
        'merge_status' => 'cannot_be_merged',
        'state' => 'closed'
    )
);

$emptyMRList = array();

$incompleteMR = array(
    (object)array(
        'iid' => 4,
        'source_project_id' => '40',
        'source_branch' => 'incomplete',
        'target_project_id' => '40',
        'target_branch' => 'master',
        'title' => 'Incomplete MR',
        'created_at' => '2023-12-01T16:00:00Z',
        'updated_at' => '2023-12-01T17:00:00Z',
        'state' => 'opened'
    )
);

$specialCharsMR = array(
    (object)array(
        'iid' => 5,
        'source_project_id' => '50',
        'source_branch' => 'special-chars',
        'target_project_id' => '50',
        'target_branch' => 'master',
        'title' => 'Fix issue with special chars: & < > " \'',
        'created_at' => '2023-12-01T18:00:00Z',
        'updated_at' => '2023-12-01T19:00:00Z',
        'merge_status' => 'can_be_merged',
        'state' => 'opened'
    )
);

r($mrTest->saveMrDataTest($testRepo, $singleMR)) && p() && e(1);
r($mrTest->saveMrDataTest($testRepo, $multipleMR)) && p() && e(1);
r($mrTest->saveMrDataTest($testRepo, $emptyMRList)) && p() && e(1);
r($mrTest->saveMrDataTest($testRepo, $incompleteMR)) && p() && e(1);
r($mrTest->saveMrDataTest($testRepo, $specialCharsMR)) && p() && e(1);