#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::apiUpdateHook();
timeout=0
cid=1

- 使用空的projectID更新gitlab群组属性message @404 Project Not Found
- 使用空的hookID更新gitlab群组属性message @404 Not found
- 使用错误gitlabID更新群组 @0
- 通过gitlabID,projectID,分支对象正确更新GitLab分支
 - 属性merge_requests_events @~~
 - 属性push_events @1

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;

$hooks = $gitlab->apiGetHooks($gitlabID, $projectID);
foreach($hooks as $hook)
{
    if($hook->url == 'http://unittest.com/api.php/v1/gitlab/webhook?repoID=1')
    {
        $hookID = $hook->id;
        break;
    }
}

$hook = new stdclass();
$hook->url                   = 'http://unittest.com/api.php/v1/gitlab/webhook?repoID=1';
$hook->merge_requests_events = 0;

$gitlabTest = new gitlabTest();
r($gitlabTest->apiUpdateHookTest($gitlabID, 0, $hookID, $hook))    && p('message') && e('404 Project Not Found'); //使用空的projectID更新gitlab群组
r($gitlabTest->apiUpdateHookTest($gitlabID, $projectID, 0, $hook)) && p('message') && e('404 Not found'); //使用空的hookID更新gitlab群组
r($gitlabTest->apiUpdateHookTest(0, $projectID, $hookID, $hook))   && p() && e('0'); //使用错误gitlabID更新群组

r($gitlabTest->apiUpdateHookTest($gitlabID, $projectID, $hookID, $hook)) && p('merge_requests_events,push_events')          && e('~~,1');         //通过gitlabID,projectID,分支对象正确更新GitLab分支
