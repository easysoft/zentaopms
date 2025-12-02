#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiCreateHook();
timeout=0
cid=16577

- 执行gitlabTest模块的apiCreateHookTest方法，参数是1, 1, $hookWithoutUrl  @0
- 执行gitlabTest模块的apiCreateHookTest方法，参数是1, 1, $emptyHook  @0
- 执行gitlabTest模块的apiCreateHookTest方法，参数是999, 1, $hook  @0
- 执行gitlabTest模块的apiCreateHookTest方法，参数是$gitlabID, 999, $hook 属性message @404 Project Not Found
- 执行gitlabTest模块的apiCreateHookTest方法，参数是$gitlabID, $projectID, $hook 属性url @http://unittest.com/api.php/v1/gitlab/webhook?repoID=1
- 执行gitlabTest模块的apiCreateHookTest方法，参数是$gitlabID, $projectID, $fullHook
 - 属性url @http://unittest.com/api.php/v1/gitlab/webhook?repoID=1
 - 属性push_events @1
 - 属性issues_events @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlabTest = new gitlabTest();

$gitlabID = 1;
$projectID = 2;

$emptyHook = new stdclass();

$hookWithoutUrl = new stdclass();
$hookWithoutUrl->push_events = true;
$hookWithoutUrl->merge_requests_events = true;

$hook = new stdclass();
$hook->url = 'http://unittest.com/api.php/v1/gitlab/webhook?repoID=1';
$hook->push_events = true;
$hook->merge_requests_events = true;

$fullHook = new stdclass();
$fullHook->url = 'http://unittest.com/api.php/v1/gitlab/webhook?repoID=1';
$fullHook->push_events = true;
$fullHook->merge_requests_events = true;
$fullHook->issues_events = true;
$fullHook->tag_push_events = true;

r($gitlabTest->apiCreateHookTest(1, 1, $hookWithoutUrl)) && p() && e('0');
r($gitlabTest->apiCreateHookTest(1, 1, $emptyHook)) && p() && e('0');
r($gitlabTest->apiCreateHookTest(999, 1, $hook)) && p() && e('0');
r($gitlabTest->apiCreateHookTest($gitlabID, 999, $hook)) && p('message') && e('404 Project Not Found');
r($gitlabTest->apiCreateHookTest($gitlabID, $projectID, $hook)) && p('url') && e('http://unittest.com/api.php/v1/gitlab/webhook?repoID=1');
r($gitlabTest->apiCreateHookTest($gitlabID, $projectID, $fullHook)) && p('url,push_events,issues_events') && e('http://unittest.com/api.php/v1/gitlab/webhook?repoID=1,1,1');