#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteTag();
cid=1
pid=1

使用空的gitlabID,projectID,分支名称删除保护分支 >> return false
使用正确的gitlabID、错误的projectID删除标签 >> 404 Project Not Found
使用正确的gitlabID、projectID，不存在的标签删除 >> 404 Tag Not Found
删除受保护的标签 >> Protected tags cannot be deleted
成功删除标签 >> return true

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$tagName   = '';

$result = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,分支名称删除保护分支

$gitlabID  = 1;
$projectID = 1;
$tagName   = 'testTagName';
$result    = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
r($result) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、错误的projectID删除标签

$projectID = 1555;
$result    = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
r($result) && p('message') && e('404 Tag Not Found'); //使用正确的gitlabID、projectID，不存在的标签删除

$tagName = 'test_tag3';
$result = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
r($result) && p('message') && e('Protected tags cannot be deleted'); //删除受保护的标签

$tagName = 'testTagName';
$_POST['tag_name'] = $tagName;
$_POST['ref']      = 'master';
$gitlab->createTag($gitlabID, $projectID);
$result = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
if(!$result or substr($result->message, 0, 2) == '20') $result = 'return true';
r($result) && p('message') && e('return true'); //成功删除标签