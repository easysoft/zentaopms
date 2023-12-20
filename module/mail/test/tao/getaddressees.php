#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getAddressees();
cid=0

- 不传入任何参数 @0
- 只传入object @0
- 只传入action @0
- 只传入objectType参数 @0
- 不传入object @0
- 不传入action @0
- 不传入objectType参数 @0
- 传入的objectType不合法 @0
- 传入的object不存在 @0
- 传入的action不存在 @0
- 获取测试单的收信人 @user3,,

- 获取测试单的抄送人 @``
- 获取文档的收信人 @admin
- 获取文档的抄送人 @user1
- 获取需求的收信人 @admin
- 获取需求的抄送人 @``
- 获取Bug的收信人 @admin
- 获取Bug的抄送人 @admin
- 获取任务的收信人 @user2
- 获取任务的抄送人 @user4
- 获取发布的收信人 @admin
- 获取发布的抄送人 @po1
- 获取review的收信人 @admin
- 获取review的抄送人 @``

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$action = zdTable('action');
$action->execution->range(1);
$action->extra->range('``,`Fix:1`');
$action->gen(2);
zdTable('history')->gen(2);
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

r($mail->getAddresseesTest('', 0, 0))          && p() && e('0'); //不传入任何参数
r($mail->getAddresseesTest('', 1, 0))          && p() && e('0'); //只传入object
r($mail->getAddresseesTest('', 0, 1))          && p() && e('0'); //只传入action
r($mail->getAddresseesTest('testtask', 0, 0))  && p() && e('0'); //只传入objectType参数
r($mail->getAddresseesTest('testtask', 0, 1))  && p() && e('0'); //不传入object
r($mail->getAddresseesTest('testtask', 1, 0))  && p() && e('0'); //不传入action
r($mail->getAddresseesTest('', 1, 1))          && p() && e('0'); //不传入objectType参数
r($mail->getAddresseesTest('test', 1, 1))      && p() && e('0'); //传入的objectType不合法
r($mail->getAddresseesTest('testtask', 10, 1)) && p() && e('0'); //传入的object不存在
r($mail->getAddresseesTest('testtask', 1, 20)) && p() && e('0'); //传入的action不存在

$review = new stdclass();
$review->auditedBy = 'admin';

$testtaskAddressees = $mail->getAddresseesTest('testtask', 1, 1);
$docAddressees = $mail->getAddresseesTest('doc', 1, 1);
$storyAddressees = $mail->getAddresseesTest('story', 1, 1);
$bugAddressees = $mail->getAddresseesTest('bug', 1, 1);
$taskAddressees = $mail->getAddresseesTest('task', 2, 1);
$releaseAddressees = $mail->getAddresseesTest('release', 1, 1);
$reviewAddressees = $mail->objectModel->getAddressees('review', $review, new stdclass());

r($testtaskAddressees[0]) && p() && e('user3,,');  //获取测试单的收信人
r($testtaskAddressees[1]) && p() && e('``');       //获取测试单的抄送人
r($docAddressees[0])      && p() && e('admin');    //获取文档的收信人
r($docAddressees[1])      && p() && e('user1');    //获取文档的抄送人
r($storyAddressees[0])    && p() && e('admin');    //获取需求的收信人
r($storyAddressees[1])    && p() && e('``');       //获取需求的抄送人
r($bugAddressees[0])      && p() && e('admin');    //获取Bug的收信人
r($bugAddressees[1])      && p() && e('admin');    //获取Bug的抄送人
r($taskAddressees[0])     && p() && e('user2');    //获取任务的收信人
r($taskAddressees[1])     && p() && e('user4');    //获取任务的抄送人
r($releaseAddressees[0])  && p() && e('admin');    //获取发布的收信人
r($releaseAddressees[1])  && p() && e('po1');      //获取发布的抄送人
r($reviewAddressees[0])   && p() && e('admin');    //获取review的收信人
r($reviewAddressees[1])   && p() && e('``');       //获取review的抄送人
