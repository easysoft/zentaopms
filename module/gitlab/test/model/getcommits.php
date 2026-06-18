#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getCommits();
timeout=0
cid=0

- 测试步骤1：获取有效仓库的提交记录 @array
- 测试步骤2：测试指定路径的提交记录查询 @array
- 测试步骤3：测试时间范围查询功能 @array
- 测试步骤4：测试分页参数处理 @array
- 测试步骤5：测试无效仓库ID的处理 @array
- 测试步骤6：测试提交数据字段完整性第0条的revision属性 @~~
第0条的0:comment属性 @~~
第0条的0:committer属性 @~~
第0条的0:time属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlab = new gitlabModelTest();

r(is_array($gitlab->getCommitsTest(1))) && p() && e('1'); // 测试步骤1：获取有效仓库的提交记录
r(is_array($gitlab->getCommitsTest(1, '/src'))) && p() && e('1'); // 测试步骤2：测试指定路径的提交记录查询
r(is_array($gitlab->getCommitsTest(1, '', null, '2023-01-01', '2023-12-31'))) && p() && e('1'); // 测试步骤3：测试时间范围查询功能
r(is_array($gitlab->getCommitsTest(1, '', (object)array('recPerPage' => 5, 'pageID' => 1)))) && p() && e('1'); // 测试步骤4：测试分页参数处理
r(count($gitlab->getCommitsTest(999)) == 0) && p() && e('1'); // 测试步骤5：测试无效仓库ID的处理
$commits = $gitlab->getCommitsTest(1);
r($commits[0]->revision) && p() && e('abc123'); // 测试步骤6：测试提交revision字段
r($commits[0]->comment) && p() && e('Initial commit'); // 测试步骤7：测试提交comment字段
r($commits[0]->committer) && p() && e('admin'); // 测试步骤8：测试提交committer字段
r($commits[0]->time) && p() && e('2023-01-01 00:00:00'); // 测试步骤9：测试提交time字段
