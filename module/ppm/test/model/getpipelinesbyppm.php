#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getPipelinesByPPM();
timeout=0
cid=0

- 执行is_array($ppmModel->getPipelinesByPPMTest($openedPPM)) ? 1 : 0 @1
- 执行is_array($ppmModel->getPipelinesByPPMTest($mergedPPM)) ? 1 : 0 @1
- 执行ppmModel模块的getPipelinesByPPMTest方法 @0
- 执行ppmModel模块的getPipelinesByPPMTest方法 @0
- 执行ppmModel模块的getPipelinesByPPMTest方法 @0
- 执行ppmModel模块的getPipelinesByPPMTest方法 @0
- 执行ppmModel模块的getPipelinesByPPMTest方法 @0
- 执行ppmModel模块的getPipelinesByPPMTest方法 @0
- 执行count($ppmModel->getPipelinesByPPMTest($openedPPM)) @0
- 执行count($ppmModel->getPipelinesByPPMTest($mergedPPM)) @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

$ppmModel  = new ppmModelTest();
$openedPPM = (object)array('id' => 81, 'repoID' => 42, 'sourceSHA' => '6bada47137f46e3b6b3792b397b714e3726e6990', 'sourceBranch' => 'feature/bbb', 'targetBranch' => 'main', 'status' => 'opened');
$mergedPPM = (object)array('id' => 81, 'repoID' => 42, 'sourceSHA' => '6bada47137f46e3b6b3792b397b714e3726e6990', 'sourceBranch' => 'feature/bbb', 'targetBranch' => 'main', 'mergeSHA' => '4444444444444444444444444444444444444444', 'status' => 'merged');

r(is_array($ppmModel->getPipelinesByPPMTest($openedPPM)) ? 1 : 0) && p() && e('1');
r(is_array($ppmModel->getPipelinesByPPMTest($mergedPPM)) ? 1 : 0) && p() && e('1');
r($ppmModel->getPipelinesByPPMTest((object)array('id' => 81, 'repoID' => 42, 'sourceBranch' => 'feature/bbb', 'targetBranch' => 'main', 'status' => 'opened'))) && p() && e('0');
r($ppmModel->getPipelinesByPPMTest((object)array('id' => 81, 'repoID' => 42, 'sourceSHA' => '6bada47137f46e3b6b3792b397b714e3726e6990', 'targetBranch' => 'main', 'status' => 'opened'))) && p() && e('0');
r($ppmModel->getPipelinesByPPMTest((object)array('id' => 81, 'repoID' => 42, 'targetBranch' => 'main', 'status' => 'opened'))) && p() && e('0');
r($ppmModel->getPipelinesByPPMTest((object)array('id' => 81, 'repoID' => 42, 'sourceBranch' => '', 'targetBranch' => 'main', 'status' => 'opened'))) && p() && e('0');
r($ppmModel->getPipelinesByPPMTest((object)array('id' => 81, 'repoID' => 42, 'sourceSHA' => '', 'sourceBranch' => '', 'status' => 'opened'))) && p() && e('0');
r($ppmModel->getPipelinesByPPMTest((object)array())) && p() && e('0');
r(count($ppmModel->getPipelinesByPPMTest($openedPPM))) && p() && e('0');
r(count($ppmModel->getPipelinesByPPMTest($mergedPPM))) && p() && e('0');
