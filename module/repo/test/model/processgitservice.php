#!/usr/bin/env php
<?php

/**

title=测试 repoModel::processGitService();
timeout=0
cid=18089

- 步骤1：正常处理Gitlab版本库
 - 属性client @https://gitlabdev.qc.oop.cc
 - 属性codePath @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
 - 属性gitService @1
- 步骤2：正常处理Gitea版本库
 - 属性codePath @https://giteadev.qc.oop.cc/gitea/unittest
 - 属性name @unittest
- 步骤3：处理另一个Gitlab版本库属性gitService @1
- 步骤4：处理serviceHost=0的版本库属性gitService @1
- 步骤5：测试getCodePath=true的Gitlab版本库属性gitService @1
- 步骤6：测试路径不存在的情况属性gitService @1
- 步骤7：测试Gitea版本库的getCodePath属性name @unittest

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repo = new repoModelTest();

// 5. 强制要求：必须包含至少7个测试步骤
r($repo->processGitServiceTest(1)) && p('client,codePath,gitService') && e('https://gitlabdev.qc.oop.cc,http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml,1'); // 步骤1：正常处理Gitlab版本库
r($repo->processGitServiceTest(3)) && p('codePath,name') && e('https://giteadev.qc.oop.cc/gitea/unittest,unittest'); // 步骤2：正常处理Gitea版本库
r($repo->processGitServiceTest(2)) && p('gitService') && e('1'); // 步骤3：处理另一个Gitlab版本库
r($repo->processGitServiceTest(5)) && p('gitService') && e('1'); // 步骤4：处理serviceHost=0的版本库
r($repo->processGitServiceTestWithCodePath(1)) && p('gitService') && e('1'); // 步骤5：测试getCodePath=true的Gitlab版本库
r($repo->processGitServiceTestWithInvalidPath(1)) && p('gitService') && e('1'); // 步骤6：测试路径不存在的情况
r($repo->processGitServiceTestWithCodePath(3)) && p('name') && e('unittest'); // 步骤7：测试Gitea版本库的getCodePath