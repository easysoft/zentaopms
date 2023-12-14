#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteTag();
timeout=0
cid=1

- 使用空的gitlabID,projectID,分支名称删除保护分支 @return false
- 使用正确的gitlabID、错误的projectID删除标签属性message @404 Project Not Found
- 使用正确的gitlabID、projectID，不存在的标签删除属性message @404 Tag Not Found
- 删除受保护的标签属性message @You are not allowed to create this tag as it is protected.
- 成功删除标签属性message @return true

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$tagName   = '';

$result = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,分支名称删除保护分支

$gitlabID  = 1;
$projectID = 1552;
$tagName   = 'testTagName';
$result    = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
r($result) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、错误的projectID删除标签

$projectID = 2;
$result    = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
r($result) && p('message') && e('404 Tag Not Found'); //使用正确的gitlabID、projectID，不存在的标签删除

$tagName = 'tag3';
$result = $gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);
r($result) && p('message') && e('You are not allowed to create this tag as it is protected.'); //删除受保护的标签

$tag = (object)array('tag_name' => 'test_tag17', 'ref' => 'master');
$gitlab->createTag($gitlabID, $projectID, $tag);
$result = $gitlab->apiDeleteTag($gitlabID, $projectID, $tag->tag_name);
if(!$result or substr($result->message, 0, 2) == '20') $result = 'return true';
r($result) && p('message') && e('return true'); //成功删除标签