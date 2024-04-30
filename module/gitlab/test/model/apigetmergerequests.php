#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetMergeRequests();
timeout=0
cid=1

*/

zenData('pipeline')->gen(4);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 18;

$result = $gitlab->apiGetMergeRequests($gitlabID, $projectID);
r(isset($result[0]->iid)) && p() && e('1'); // 验证获取的第一个合并请求是否含有iid属性
r(count($result) > 0)     && p() && e('1'); // 验证获取的合并请求数量是否大于0
