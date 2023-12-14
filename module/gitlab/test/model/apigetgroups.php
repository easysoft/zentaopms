#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetGroups();
timeout=0
cid=1

- 使用空的数据查询群组 @return null
- 使用正确的gitlabID查询群组 @return true
- 使用错误的orderBy查询群组 @return true
- 通过gitlabID,orderBy查询群组 @return true
- 通过错误的minRole查询群组 @return true

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 0;
$orderBy  = '';
$minRole  = ''; 

$result = $gitlab->apiGetGroups($gitlabID, $orderBy, $minRole);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); //使用空的数据查询群组

$gitlabID = 1;
$result   = $gitlab->apiGetGroups($gitlabID, $orderBy, $minRole);
if($result) $result = 'return true';
r($result) && p() && e('return true'); //使用正确的gitlabID查询群组

$orderBy = 'abc';
$result  = $gitlab->apiGetGroups($gitlabID, $orderBy, $minRole);
if(is_array($result)) $result = 'return true';
r($result) && p() && e('return true'); //使用错误的orderBy查询群组

$orderBy = 'name_asc';
$minRole = 'owner';
$result  = $gitlab->apiGetGroups($gitlabID, $orderBy, $minRole);
if(is_array($result)) $result = 'return true';
r($result) && p() && e('return true'); //通过gitlabID,orderBy查询群组

$minRole = 'abc';
$result  = $gitlab->apiGetGroups($gitlabID, $orderBy, $minRole);
if(is_array($result)) $result = 'return true';
r($result) && p() && e('return true'); //通过错误的minRole查询群组