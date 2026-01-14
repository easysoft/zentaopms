#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 gitlabModel::apiCreatePipeline();
timeout=0
cid=16579

- 执行gitlabTest模块的apiCreatePipelineTest方法，参数是$invalidGitlabID, $invalidProjectID, $emptyPipeline  @0
- 执行gitlabTest模块的apiCreatePipelineTest方法，参数是$invalidGitlabID, $invalidProjectID, $basicPipeline  @0
- 执行gitlabTest模块的apiCreatePipelineTest方法，参数是$gitlabID, $invalidProjectID, $basicPipeline  @0
- 执行gitlabTest模块的apiCreatePipelineTest方法，参数是$negativeGitlabID, $negativeProjectID, $basicPipeline  @0
- 执行gitlabTest模块的apiCreatePipelineTest方法，参数是$gitlabID, $projectID, $basicPipeline  @0
- 执行gitlabTest模块的apiCreatePipelineTest方法，参数是$gitlabID, $projectID, $complexPipeline  @0
- 执行gitlabTest模块的apiCreatePipelineTest方法，参数是$gitlabID, $projectID, json_decode  @0

*/

zenData('pipeline')->gen(5);

su('admin');

$gitlabTest = new gitlabModelTest();

$gitlabID = 1;
$projectID = 2;
$invalidGitlabID = 0;
$invalidProjectID = 0;
$negativeGitlabID = -1;
$negativeProjectID = -1;

$emptyPipeline = new stdclass();

$basicPipeline = new stdclass();
$basicPipeline->ref = 'master';

$complexPipeline = new stdclass();
$complexPipeline->ref = 'develop';
$complexPipeline->variables = array(
    array('key' => 'ENV', 'value' => 'production'),
    array('key' => 'VERSION', 'value' => '1.0.0')
);

$stringParams = '{"ref": "master", "variables": [{"key": "TEST", "value": "true"}]}';

r($gitlabTest->apiCreatePipelineTest($invalidGitlabID, $invalidProjectID, $emptyPipeline)) && p() && e('0');
r($gitlabTest->apiCreatePipelineTest($invalidGitlabID, $invalidProjectID, $basicPipeline)) && p() && e('0');
r($gitlabTest->apiCreatePipelineTest($gitlabID, $invalidProjectID, $basicPipeline)) && p() && e('0');
r($gitlabTest->apiCreatePipelineTest($negativeGitlabID, $negativeProjectID, $basicPipeline)) && p() && e('0');
r($gitlabTest->apiCreatePipelineTest($gitlabID, $projectID, $basicPipeline)) && p() && e('0');
r($gitlabTest->apiCreatePipelineTest($gitlabID, $projectID, $complexPipeline)) && p() && e('0');
r($gitlabTest->apiCreatePipelineTest($gitlabID, $projectID, json_decode($stringParams))) && p() && e('0');