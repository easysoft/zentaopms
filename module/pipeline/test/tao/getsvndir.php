#!/usr/bin/env php
<?php

/**

title=测试 jobTao::getSvnDir();
timeout=0
cid=16858

- 执行jobTest模块的getSvnDirTest方法，参数是$job1, $repo1, array 属性svnDir @tags/v1.0
- 执行jobTest模块的getSvnDirTest方法，参数是$job2, $repo2, array 属性svnDir @~~
- 执行jobTest模块的getSvnDirTest方法，参数是$job3, $repo3, array 属性svnDir @~~
- 执行jobTest模块的getSvnDirTest方法，参数是$job4, $repo4, array 属性svnDir @~~
- 执行jobTest模块的getSvnDirTest方法，参数是$job5, $repo5, array 属性svnDir @tags/v2.0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTest();

// 4. 准备测试数据

// 测试步骤1：triggerType包含tag且SCM为Subversion的正常情况
$job1 = new stdclass();
$job1->triggerType = 'tag_create';
$repo1 = new stdclass();
$repo1->SCM = 'Subversion';
r($jobTest->getSvnDirTest($job1, $repo1, array('trunk', 'tags', 'tags/v1.0'))) && p('svnDir') && e('tags/v1.0'); 

// 测试步骤2：triggerType不包含tag的情况
$job2 = new stdclass();
$job2->triggerType = 'commit';
$repo2 = new stdclass();
$repo2->SCM = 'Subversion';
r($jobTest->getSvnDirTest($job2, $repo2, array('trunk', 'branches', 'feature'))) && p('svnDir') && e('~~'); 

// 测试步骤3：SCM不为Subversion的情况
$job3 = new stdclass();
$job3->triggerType = 'tag_create';
$repo3 = new stdclass();
$repo3->SCM = 'Git';
r($jobTest->getSvnDirTest($job3, $repo3, array('master', 'develop', 'release'))) && p('svnDir') && e('~~'); 

// 测试步骤4：$_POST['svnDir']为空的情况
$job4 = new stdclass();
$job4->triggerType = 'tag_create';
$repo4 = new stdclass();
$repo4->SCM = 'Subversion';
r($jobTest->getSvnDirTest($job4, $repo4, array())) && p('svnDir') && e('~~'); 

// 测试步骤5：$_POST['svnDir']最后元素为'/'的特殊处理
$job5 = new stdclass();
$job5->triggerType = 'tag_create';
$repo5 = new stdclass();
$repo5->SCM = 'Subversion';
r($jobTest->getSvnDirTest($job5, $repo5, array('trunk', 'tags/v2.0', '/'))) && p('svnDir') && e('tags/v2.0');