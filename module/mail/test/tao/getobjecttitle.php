#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getObjectTitle();
cid=0

- 不传入任何参数 @0
- 只传入object @0
- 只传入objectType参数 @0
- 传入的objectType不合法 @0
- 获取测试单的邮件标题 @测试单1
- 获取文档的邮件标题 @文档标题1
- 获取需求的邮件标题 @用户需求版本一1
- 获取Bug的邮件标题 @BUG1
- 获取任务的邮件标题 @开发任务12
- 获取发布的邮件标题 @产品正常的正常的发布1
- 获取卡片的邮件标题 @卡片1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$testtask = zdTable('testtask');
$testtask->createdBy->range('admin');
$testtask->createdDate->range('`' . date('Y-m-d H:i:s') . '`');
$testtask->gen(2);
$doc = zdTable('doc');
$doc->version->range('1');
$doc->mailto->range('`admin,user1`');
$doc->gen(2);
zdTable('doccontent')->gen(5);
$task = zdTable('task');
$task->assignedTo->range('user1,user2');
$task->mailto->range('user3,user4');
$task->gen(2);
$story = zdTable('story');
$story->version->range('1');
$story->gen(2);
zdTable('storyspec')->gen(2);
zdTable('bug')->gen(2);
zdTable('kanbancard')->gen(2);
zdTable('release')->gen(2);
zdTable('product')->gen(2);
$project = zdTable('project');
$project->id->range('101-105');
$project->name->range('1-5')->prefix('迭代');
$project->gen(2);

$mail = new mailTest();

r($mail->getObjectTitleTest('', 0))          && p() && e('0'); //不传入任何参数
r($mail->getObjectTitleTest('', 1))          && p() && e('0'); //只传入object
r($mail->getObjectTitleTest('testtask', 0))  && p() && e('0'); //只传入objectType参数
r($mail->getObjectTitleTest('test', 1))      && p() && e('0'); //传入的objectType不合法

r($mail->getObjectTitleTest('testtask', 1))   && p() && e('测试单1');               //获取测试单的邮件标题
r($mail->getObjectTitleTest('doc', 1))        && p() && e('文档标题1');             //获取文档的邮件标题
r($mail->getObjectTitleTest('story', 1))      && p() && e('用户需求版本一1');       //获取需求的邮件标题
r($mail->getObjectTitleTest('bug', 1))        && p() && e('BUG1');                  //获取Bug的邮件标题
r($mail->getObjectTitleTest('task', 2))       && p() && e('开发任务12');            //获取任务的邮件标题
r($mail->getObjectTitleTest('release', 1))    && p() && e('产品正常的正常的发布1'); //获取发布的邮件标题
r($mail->getObjectTitleTest('kanbancard', 1)) && p() && e('卡片1');                 //获取卡片的邮件标题
