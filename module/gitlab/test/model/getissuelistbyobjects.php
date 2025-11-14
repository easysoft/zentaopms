#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getIssueListByObjects();
timeout=0
cid=16651

- 步骤1：获取多个任务的issue关联
 - 第18条的issueID属性 @4
 - 第19条的issueID属性 @1
 - 第18条的gitlabID属性 @1
 - 第19条的gitlabID属性 @1
- 步骤2：获取单个bug的issue关联
 - 第5条的issueID属性 @3
 - 第5条的gitlabID属性 @1
 - 第5条的projectID属性 @2
- 步骤3：获取单个story的issue关联
 - 第8条的issueID属性 @2
 - 第8条的gitlabID属性 @1
 - 第8条的projectID属性 @2
- 步骤4：测试空数组 @0
- 步骤5：测试不存在的对象ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('relation')->loadYaml('relation')->gen(4);

su('admin');

$gitlabTest = new gitlabTest();

r($gitlabTest->getIssueListByObjectsTest('task', array(18, 19))) && p('18:issueID;19:issueID;18:gitlabID;19:gitlabID') && e('4,1,1,1'); // 步骤1：获取多个任务的issue关联
r($gitlabTest->getIssueListByObjectsTest('bug', array(5))) && p('5:issueID;5:gitlabID;5:projectID') && e('3,1,2'); // 步骤2：获取单个bug的issue关联
r($gitlabTest->getIssueListByObjectsTest('story', array(8))) && p('8:issueID;8:gitlabID;8:projectID') && e('2,1,2'); // 步骤3：获取单个story的issue关联
r($gitlabTest->getIssueListByObjectsTest('task', array())) && p() && e(0); // 步骤4：测试空数组
r($gitlabTest->getIssueListByObjectsTest('task', array(999, 1000))) && p() && e(0); // 步骤5：测试不存在的对象ID