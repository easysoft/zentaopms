#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::editGroup();
timeout=0
cid=16644

- 执行gitlabTest模块的editGroupTest方法，参数是$gitlabID, $emptyNameGroup 第name条的0属性 @群组名称不能为空
- 执行gitlabTest模块的editGroupTest方法，参数是$gitlabID, $validGroup  @1
- 执行gitlabTest模块的editGroupTest方法，参数是999, $invalidGitlabGroup  @0
- 执行gitlabTest模块的editGroupTest方法，参数是$gitlabID, $invalidGroup  @0
- 执行gitlabTest模块的editGroupTest方法，参数是$gitlabID, $emptyStringGroup 第name条的0属性 @群组名称不能为空

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

su('admin');

$gitlabTest = new gitlabTest();

$gitlabID = 1;

// 测试步骤1：使用空name字段编辑群组
$emptyNameGroup = new stdclass();
$emptyNameGroup->id = 123;
$emptyNameGroup->description = 'test description';
r($gitlabTest->editGroupTest($gitlabID, $emptyNameGroup)) && p('name:0') && e('群组名称不能为空');

// 测试步骤2：使用有效参数正常编辑群组
$validGroup = new stdclass();
$validGroup->id = 123;
$validGroup->name = 'updatedTestGroup17';
$validGroup->description = 'updated description';
r($gitlabTest->editGroupTest($gitlabID, $validGroup)) && p() && e(1);

// 测试步骤3：使用无效的gitlabID编辑群组
$invalidGitlabGroup = new stdclass();
$invalidGitlabGroup->id = 123;
$invalidGitlabGroup->name = 'testGroup';
r($gitlabTest->editGroupTest(999, $invalidGitlabGroup)) && p() && e(0);

// 测试步骤4：使用无效的groupID编辑群组
$invalidGroup = new stdclass();
$invalidGroup->id = 99999;
$invalidGroup->name = 'testGroup';
r($gitlabTest->editGroupTest($gitlabID, $invalidGroup)) && p() && e(0);

// 测试步骤5：测试name字段边界值（空字符串）
$emptyStringGroup = new stdclass();
$emptyStringGroup->id = 123;
$emptyStringGroup->name = '';
$emptyStringGroup->description = 'test description';
r($gitlabTest->editGroupTest($gitlabID, $emptyStringGroup)) && p('name:0') && e('群组名称不能为空');