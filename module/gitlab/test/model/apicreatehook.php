#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateHook();
timeout=0
cid=1

- 使用空的gitlabID,projectID,hook对象创建GitLabhook @0
- 使用空的gitlabID、projectID,正确的hook对象创建GitLabhook @0
- 使用正确的gitlabID、hook信息，错误的projectID创建hook属性message @404 Project Not Found
- 通过gitlabID,projectID,hook对象正确创建GitLabhook属性url @http://unittest.com/api.php/v1/gitlab/webhook?repoID=1

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$emptyHook = new stdclass();

$hook = new stdclass();
$hook->url                   = 'http://unittest.com/api.php/v1/gitlab/webhook?repoID=1';
$hook->push_events           = true;
$hook->merge_requests_events = true;

r($gitlab->apiCreateHook(0, 0, $emptyHook))             && p()          && e('0'); //使用空的gitlabID,projectID,hook对象创建GitLabhook
r($gitlab->apiCreateHook(0, 0, $hook))                  && p()          && e('0'); //使用空的gitlabID、projectID,正确的hook对象创建GitLabhook
r($gitlab->apiCreateHook($gitlabID, 0, $hook))          && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、hook信息，错误的projectID创建hook
r($gitlab->apiCreateHook($gitlabID, $projectID, $hook)) && p('url')     && e('http://unittest.com/api.php/v1/gitlab/webhook?repoID=1');         //通过gitlabID,projectID,hook对象正确创建GitLabhook