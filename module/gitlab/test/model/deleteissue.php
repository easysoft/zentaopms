#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 gitlabModel::deleteIssue();
timeout=0
cid=16643

- 测试步骤1：删除存在的任务关联Issue @1
- 测试步骤2：删除存在的Bug关联Issue @1
- 测试步骤3：删除存在的需求关联Issue @1
- 测试步骤4：删除不存在关联关系的对象Issue @0
- 测试步骤5：使用无效objectType删除Issue @0

*/

zenData('relation')->loadYaml('relation')->gen(4);

$gitlab = new gitlabModelTest();

r($gitlab->deleteIssueTest('task', 18, 5)) && p() && e('1');   // 测试步骤1：删除存在的任务关联Issue
r($gitlab->deleteIssueTest('bug', 5, 5)) && p() && e('1');     // 测试步骤2：删除存在的Bug关联Issue
r($gitlab->deleteIssueTest('story', 8, 5)) && p() && e('1');   // 测试步骤3：删除存在的需求关联Issue
r($gitlab->deleteIssueTest('task', 999, 5)) && p() && e('0');  // 测试步骤4：删除不存在关联关系的对象Issue
r($gitlab->deleteIssueTest('invalid', 18, 5)) && p() && e('0'); // 测试步骤5：使用无效objectType删除Issue