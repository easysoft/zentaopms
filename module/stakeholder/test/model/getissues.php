#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getIssues();
cid=1

- 获取项目ID=0下干系人提出的问题列表。
 - 第1条的project属性 @60
 - 第1条的title属性 @问题标题1
 - 第1条的activity属性 @1
 - 第1条的owner属性 @admin
- 获取项目ID=11下干系人提出的问题列表。
 - 第11条的project属性 @11
 - 第11条的title属性 @问题标题11
 - 第11条的activity属性 @~~
 - 第11条的owner属性 @user10
- 获取项目ID不存在下干系人提出的问题列表。
 - 第1条的project属性 @60
 - 第1条的title属性 @问题标题1
 - 第1条的activity属性 @1
 - 第1条的owner属性 @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('user')->gen(20);
zdTable('stakeholder')->config('stakeholder')->gen(20);
zdTable('issue')->config('issue')->gen(20);

$projectIds = array(0, 11, 100);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getIssuesTest($projectIds[0])) && p('1:project,title,activity,owner')  && e('60,问题标题1,1,admin');    // 获取项目ID=0下干系人提出的问题列表。
r($stakeholderTester->getIssuesTest($projectIds[1])) && p('11:project,title,activity,owner') && e('11,问题标题11,~~,user10'); // 获取项目ID=11下干系人提出的问题列表。
r($stakeholderTester->getIssuesTest($projectIds[2])) && p('1:project,title,activity,owner')  && e('60,问题标题1,1,admin');    // 获取项目ID不存在下干系人提出的问题列表。
