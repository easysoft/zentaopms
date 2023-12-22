#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getStakeholderIssue();
cid=1

- 获取项目ID=0、用户名为空的问题信息 @0
- 获取项目ID=0、用户名=admin的问题信息 @0
- 获取项目ID=0、用户名=test01的问题信息 @0
- 获取项目ID=60、用户名为空的问题信息 @0
- 获取项目ID=60、用户名=admin的问题信息
 - 第0条的project属性 @60
 - 第0条的title属性 @问题标题1
 - 第0条的activity属性 @1
 - 第0条的owner属性 @admin
- 获取项目ID=60、用户名不存在的问题信息 @0
- 获取项目ID不存在、用户名为空的问题信息 @0
- 获取项目ID不存在、用户名=admin的问题信息 @0
- 获取项目ID不存在、用户名=test01的问题信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('user')->gen(20);
zdTable('issue')->config('issue')->gen(20);

$projectIds = array(0, 60, 100);
$accounts   = array('','admin','test01');

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getStakeholderIssueTest($projectIds[0], $accounts[0])) && p()                                 && e('0');                    // 获取项目ID=0、用户名为空的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[0], $accounts[1])) && p()                                 && e('0');                    // 获取项目ID=0、用户名=admin的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[0], $accounts[2])) && p()                                 && e('0');                    // 获取项目ID=0、用户名=test01的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[1], $accounts[0])) && p()                                 && e('0');                    // 获取项目ID=60、用户名为空的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[1], $accounts[1])) && p('0:project,title,activity,owner') && e('60,问题标题1,1,admin'); // 获取项目ID=60、用户名=admin的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[1], $accounts[2])) && p()                                 && e('0');                    // 获取项目ID=60、用户名不存在的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[2], $accounts[0])) && p()                                 && e('0');                    // 获取项目ID不存在、用户名为空的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[2], $accounts[1])) && p()                                 && e('0');                    // 获取项目ID不存在、用户名=admin的问题信息
r($stakeholderTester->getStakeholderIssueTest($projectIds[2], $accounts[2])) && p()                                 && e('0');                    // 获取项目ID不存在、用户名=test01的问题信息
