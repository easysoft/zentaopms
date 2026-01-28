#!/usr/bin/env php
<?php

/**

title=测试 repoModel::processGitService();
timeout=0
cid=18089

- 步骤1：正常处理Gitlab版本库
 - 属性client @git
 - 属性codePath @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
- 步骤2：正常处理Gitea版本库
 - 属性codePath @/var/www/html/gitlab/test2/zentaopms/www/data/repo/unittest_gitea
 - 属性name @unittest
- 步骤3：处理另一个Gitlab版本库属性serviceProject @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('pipeline')->gen(0);
zenData('repo')->loadYaml('repo')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repo = new repoModelTest();

// 5. 强制要求：必须包含至少7个测试步骤
r($repo->processGitServiceTest(1)) && p('client,codePath') && e('git,http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'); // 步骤1：正常处理Gitlab版本库
r($repo->processGitServiceTest(3)) && p('codePath,name') && e('/var/www/html/gitlab/test2/zentaopms/www/data/repo/unittest_gitea,unittest'); // 步骤2：正常处理Gitea版本库
r($repo->processGitServiceTest(2)) && p('serviceProject') && e('1'); // 步骤3：处理另一个Gitlab版本库
