#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetLabels();
timeout=0
cid=1

- 通过gitlabID,projectID,获取GitLab label列表 @1
- 通过gitlabID,projectID,获取GitLab label数量 @1
- 当前项目没有label时,获取GitLab label列表 @0
- 当gitlabID存在,projectID不存在时,获取GitLab label列表属性message @404 Project Not Found
- 当gitlabID,projectID都为0时,获取GitLab label列表 @0

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$result    = $gitlab->apiGetLabels($gitlabID, $projectID);

r(isset(array_shift($result)->text_color)) && p() && e('1'); //通过gitlabID,projectID,获取GitLab label列表
r(count($result) > 0)                      && p() && e('1'); //通过gitlabID,projectID,获取GitLab label数量

$projectID = 1;
r(count($gitlab->apiGetLabels($gitlabID, $projectID))) && p()          && e('0'); //当前项目没有label时,获取GitLab label列表
r($gitlab->apiGetLabels($gitlabID, 0))                 && p('message') && e('404 Project Not Found'); //当gitlabID存在,projectID不存在时,获取GitLab label列表
r($gitlab->apiGetLabels(0, 0))                         && p()          && e('0'); //当gitlabID,projectID都为0时,获取GitLab label列表