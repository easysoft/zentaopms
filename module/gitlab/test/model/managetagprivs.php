#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::manageTagPrivs();
timeout=0
cid=16664

- 步骤1：正常标签权限管理（无已有保护标签） @fail
- 步骤2：管理包含已有保护标签的情况 @fail
- 步骤3：使用无效的GitLabID @success
- 步骤4：使用无效的项目ID @success
- 步骤5：空标签数据处理 @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlab = new gitlabTest();

// 步骤1：正常标签权限管理（无已有保护标签）
$_POST['name'] = array('release-v1.0', 'stable');
$_POST['createAccess'] = array('40', '30');
$result1 = $gitlab->manageTagPrivsTest(1, 2, array());

// 步骤2：管理包含已有保护标签的情况
$protected = array();
$protected['release-v1.0'] = (object)array('createAccess' => 30);
$protected['old-tag'] = (object)array('createAccess' => 40);
$_POST['name'] = array('release-v1.0', 'new-tag');
$_POST['createAccess'] = array('40', '30');
$result2 = $gitlab->manageTagPrivsTest(1, 2, $protected);

// 步骤3：使用无效的GitLabID
$_POST['name'] = array('test-tag');
$_POST['createAccess'] = array('40');
$result3 = $gitlab->manageTagPrivsTest(0, 2, array());

// 步骤4：使用无效的项目ID
$_POST['name'] = array('test-tag');
$_POST['createAccess'] = array('40');
$result4 = $gitlab->manageTagPrivsTest(1, 0, array());

// 步骤5：空标签数据处理
$_POST['name'] = array();
$_POST['createAccess'] = array();
$result5 = $gitlab->manageTagPrivsTest(1, 2, array());

r($result1) && p('') && e('fail'); // 步骤1：正常标签权限管理（无已有保护标签）
r($result2) && p('') && e('fail'); // 步骤2：管理包含已有保护标签的情况
r($result3) && p('') && e('success'); // 步骤3：使用无效的GitLabID
r($result4) && p('') && e('success'); // 步骤4：使用无效的项目ID
r($result5) && p('') && e('success'); // 步骤5：空标签数据处理