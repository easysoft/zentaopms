#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteTagPriv();
timeout=0
cid=1

- 使用空的gitlabID,projectID,标签名称删除保护标签 @return false
- 使用正确的gitlabID、保护标签信息，错误的projectID删除保护标签属性message @404 Project Not Found
- 使用错误的保护标签信息删除保护标签属性message @404 Not found
- 通过gitlabID,projectID,标签名称正确删除保护标签 @return true

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$tag    = '';

$result = $gitlab->apiDeleteTagPriv($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,标签名称删除保护标签

$gitlabID = 1;
$tag      = '2021/12/30';
$result   = $gitlab->apiDeleteTagPriv($gitlabID, $projectID, $tag);
r($result) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、保护标签信息，错误的projectID删除保护标签

$projectID = 2;
$tag       = 'masters';
$result    = $gitlab->apiDeleteTagPriv($gitlabID, $projectID, $tag);
r($result) && p('message') && e('404 Not found'); //使用错误的保护标签信息删除保护标签

$projectID = 2;
$tag       = (object)array('name' => 'test_tag17', 'create_access_level' => '40');
$gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag);
$result    = $gitlab->apiDeleteTagPriv($gitlabID, $projectID, $tag->name);
if(!$result or substr($result->message, 0, 2) == '20') $result = 'return true';
r($result) && p() && e('return true');  //通过gitlabID,projectID,标签名称正确删除保护标签