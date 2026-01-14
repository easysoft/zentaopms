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

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('repohistory')->gen(10);

su('admin');

$gitlab = new gitlabModelTest();

r($gitlab->getCommitsTest(1)) && p() && e('array'); // 测试步骤1：获取有效仓库的提交记录
r($gitlab->getCommitsTest(1, '/src')) && p() && e('array'); // 测试步骤2：测试指定路径的提交记录查询
r($gitlab->getCommitsTest(1, '', null, '2023-01-01', '2023-12-31')) && p() && e('array'); // 测试步骤3：测试时间范围查询功能
r($gitlab->getCommitsTest(1, '', (object)array('recPerPage' => 5, 'pageID' => 1))) && p() && e('array'); // 测试步骤4：测试分页参数处理
r($gitlab->getCommitsTest(999)) && p() && e('array'); // 测试步骤5：测试无效仓库ID的处理
r($gitlab->getCommitsTest(1)) && p('0:revision,0:comment,0:committer,0:time') && e('~~'); // 测试步骤6：测试提交数据字段完整性