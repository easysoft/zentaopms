#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteBranchPriv();
timeout=0
cid=1

- 执行$result @return false
- 执行$result属性message @404 Project Not Found
- 执行$result @return null
- 执行$result属性message @404 Not found
- 执行$result属性message @404 Not found

*/

zenData('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 1;
$branch    = 'master';

$result = $gitlab->apiDeleteBranchPriv($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');

$gitlabID = 1;
$projectID = 999;
$result = $gitlab->apiDeleteBranchPriv($gitlabID, $projectID, $branch);
r($result) && p('message') && e('404 Project Not Found');

$projectID = 2;
$branch = 'branch1';
$result = $gitlab->apiDeleteBranchPriv($gitlabID, $projectID, $branch);
if(!$result or substr($result->message, 0, 2) == '20') $result = 'return null';
r($result) && p() && e('return null');

$branch = 'nonexistent';
$result = $gitlab->apiDeleteBranchPriv($gitlabID, $projectID, $branch);
r($result) && p('message') && e('404 Not found');

$branch = 'feature/test-branch';
$result = $gitlab->apiDeleteBranchPriv($gitlabID, $projectID, $branch);
r($result) && p('message') && e('404 Not found');