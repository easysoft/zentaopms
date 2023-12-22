#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 gitlabModel::apiDeleteHook();
timeout=0
cid=1

- 使用空的projectID删除gitlab群组属性message @404 Project Not Found
- 使用空的hookID删除gitlab群组属性message @404 Not found
- 使用错误gitlabID删除群组 @0
- 通过gitlabID,projectID,分支对象正确删除GitLab分支 @1
- 使用重复的分支信息删除分支属性message @404 Not found

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

r($gitlab->apiDeleteHook($gitlabID, 0, $hookID))    && p('message') && e('404 Project Not Found'); //使用空的projectID删除gitlab群组
r($gitlab->apiDeleteHook($gitlabID, $projectID, 0)) && p('message') && e('404 Not found'); //使用空的hookID删除gitlab群组
r($gitlab->apiDeleteHook(0, $projectID, $hookID))   && p() && e('0'); //使用错误gitlabID删除群组

$result = $gitlab->apiDeleteHook($gitlabID, $projectID, $hookID);
if(is_null($result)) $result = true;
r($result)                                                 && p()          && e('1');         //通过gitlabID,projectID,分支对象正确删除GitLab分支
r( $gitlab->apiDeleteHook($gitlabID, $projectID, $hookID)) && p('message') && e('404 Not found'); //使用重复的分支信息删除分支