#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetGroupMembers();
timeout=0
cid=1

- 使用空的数据查询群组用户 @return null
- 使用空的groupID查询群组用户 @return null
- 使用错误的groupID查询群组用户 @return null
- 通过gitlabID,groupID查询群组用户 @return true
- 通过gitlabID,groupID,userID查询群组用户 @return true

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 0;
$groupID  = 0;
$userID   = 0; 

$result = $gitlab->apiGetGroupMembers($gitlabID, $groupID, $userID);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); //使用空的数据查询群组用户

$gitlabID = 1;
$result = $gitlab->apiGetGroupMembers($gitlabID, $groupID, $userID);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); //使用空的groupID查询群组用户

$groupID = 1;
$result = $gitlab->apiGetGroupMembers($gitlabID, $groupID, $userID);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); //使用错误的groupID查询群组用户

$groupID = 2;
$result = $gitlab->apiGetGroupMembers($gitlabID, $groupID, $userID);
if(is_array($result)) $result = 'return true';
r($result) && p() && e('return true'); //通过gitlabID,groupID查询群组用户

$userID = 1;
$result = $gitlab->apiGetGroupMembers($gitlabID, $groupID, $userID);
if(is_array($result) && count($result) == 1) $result = 'return true';
r($result) && p() && e('return true'); //通过gitlabID,groupID,userID查询群组用户