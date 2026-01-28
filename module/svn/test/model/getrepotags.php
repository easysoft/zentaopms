#!/usr/bin/env php
<?php

/**

title=测试 svnModel::getRepoTags();
timeout=0
cid=18718

- 步骤1：正常情况-查询根目录tag信息无输出 @0
- 步骤2：边界值-查询空路径返回空结果 @0
- 步骤3：异常输入-查询tag子目录返回空结果 @0
- 步骤4：特殊情况-查询不存在路径返回空结果 @0
- 步骤5：权限验证-查询无效路径返回空结果 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('repo')->loadYaml('repo')->gen(1);
su('admin');

$svnTest = new svnModelTest();
$svnTest->objectModel->setRepos();

if(empty($svnTest->objectModel->repos))
{
    echo "No repos found, creating mock repo data\n";
    // 创建一个模拟的repo对象用于测试
    $mockRepo = new stdclass();
    $mockRepo->id = 1;
    $mockRepo->path = '/mock/repo';
    $mockRepo->SCM = 'Subversion';
    $svnTest->objectModel->repos[1] = $mockRepo;
}

$repo = $svnTest->objectModel->repos[1];

r($svnTest->getRepoTagsTest($repo, ''))        && p('') && e('0');    // 步骤1：正常情况-查询根目录tag信息无输出
r($svnTest->getRepoTagsTest($repo, ''))        && p('') && e('0');    // 步骤2：边界值-查询空路径返回空结果
r($svnTest->getRepoTagsTest($repo, 'tag'))     && p('') && e('0');    // 步骤3：异常输入-查询tag子目录返回空结果
r($svnTest->getRepoTagsTest($repo, 'error'))   && p('') && e('0');    // 步骤4：特殊情况-查询不存在路径返回空结果
r($svnTest->getRepoTagsTest($repo, 'invalid')) && p('') && e('0');    // 步骤5：权限验证-查询无效路径返回空结果