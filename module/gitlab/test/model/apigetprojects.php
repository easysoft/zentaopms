#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetProjects();
timeout=0
cid=1

- 通过gitlabID获取GitLab项目列表 @1
- 通过gitlabID获取GitLab项目数量 @1
- 通过gitlabID获取GitLab项目列表是否有visibility字段信息。 @0
- 当gitlabID为0时,获取GitLab项目列表 @return empty
- 通过gitlabID simple字段为false获取GitLab项目列表第0条的visibility属性 @private
- 通过gitlabID minID为4获取GitLab项目列表第0条的name属性 @privateTest
- 通过gitlabID maxID为4获取GitLab项目列表第0条的name属性 @testHtml

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$orderBy   = 'id_desc';
$simple    = 'true';

$result = $gitlab->apiGetProjects($gitlabID, $simple);
r(isset($result[0]->name)) && p() && e('1'); //通过gitlabID获取GitLab项目列表
r(count($result) > 0)      && p() && e('1'); //通过gitlabID获取GitLab项目数量
r(isset($result[0]->visibility)) && p() && e('0'); //通过gitlabID获取GitLab项目列表是否有visibility字段信息。

$gitlabID  = 0;
$result    = $gitlab->apiGetProjects($gitlabID, $simple);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID为0时,获取GitLab项目列表

$gitlabID = 1;
$simple   = 'false';
$result   = $gitlab->apiGetProjects($gitlabID, $simple);
r($result) && p('0:visibility') && e('private'); //通过gitlabID simple字段为false获取GitLab项目列表

$simple = 'true';
$minID  = 4;
$result = $gitlab->apiGetProjects($gitlabID, $simple, $minID);
r($result) && p('0:name') && e('privateTest'); //通过gitlabID minID为4获取GitLab项目列表

$simple = 'true';
$maxID  = 2;
$result = $gitlab->apiGetProjects($gitlabID, $simple, 0, $maxID);
r($result) && p('0:name') && e('testHtml'); //通过gitlabID maxID为4获取GitLab项目列表