#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetProjectsPager();
timeout=0
cid=1

- 通过gitlabID,获取GitLab项目列表 @1
- 通过gitlabID,获取GitLab项目数量 @1
- 当gitlabID为0时,获取GitLab项目列表 @return empty
- 通过gitlabID,按项目名升序获取GitLab项目列表第1条的name属性 @testHtml
- 通过gitlabID,搜索字符'private'获取GitLab项目列表第0条的name属性 @privateProject

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');
$gitlab->app->moduleName = 'gitlab';
$gitlab->app->methodName = 'browse';

$gitlabID  = 1;
$orderBy   = 'id_desc';
$keyword   = '';

$pager = new stdclass();
$pager->recPerPage = 5;
$pager->pageID     = 1;

$result = $gitlab->apiGetProjectsPager($gitlabID, $keyword, $orderBy, $pager);
r(isset($result['projects'][0]->name)) && p() && e('1'); //通过gitlabID,获取GitLab项目列表
r(count($result['projects']) > 0)      && p() && e('1'); //通过gitlabID,获取GitLab项目数量

$gitlabID  = 0;
$result    = $gitlab->apiGetProjectsPager($gitlabID, $keyword, $orderBy, $pager);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID为0时,获取GitLab项目列表

$gitlabID  = 1;
$orderBy   = 'id_asc';
$result    = $gitlab->apiGetProjectsPager($gitlabID, $keyword, $orderBy, $pager);
r($result['projects']) && p('1:name') && e('testHtml'); //通过gitlabID,按项目名升序获取GitLab项目列表

$keyword = 'private';
$result  = $gitlab->apiGetProjectsPager($gitlabID, $keyword, $orderBy, $pager);
r($result['projects']) && p('0:name') && e('privateProject'); //通过gitlabID,搜索字符'private'获取GitLab项目列表