#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildEditForm();
timeout=0
cid=18126

- 步骤1：编辑 Gitlab 版本库属性title @代码库-编辑
- 步骤2：编辑 Gitlab 版本库属性repoName @testHtml
- 步骤3：编辑 Gitlab 版本库属性projectName @testHtml
- 步骤4：编辑 SVN 版本库属性client @svn
- 步骤5：objectID 为 0 的情况属性objectID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zenData('pipeline')->gen(5);
zenData('product')->gen(20);
zenData('project')->gen(20);
zenData('projectproduct')->gen(20);
zenData('group')->gen(5);
zenData('user')->gen(10);
zenData('repo')->loadYaml('repo_buildeditform', false, 2)->gen(5);

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->buildEditFormTest(1, 2)) && p('title') && e('代码库-编辑');
r($repoZenTest->buildEditFormTest(1, 2)) && p('repoName') && e('723test');
r($repoZenTest->buildEditFormTest(1, 2)) && p('projectName') && e('723test');
r($repoZenTest->buildEditFormTest(5, 3)) && p('client') && e('https://gitlabdev.qc.oop.cc');
r($repoZenTest->buildEditFormTest(2, 0)) && p('objectID') && e('0');
