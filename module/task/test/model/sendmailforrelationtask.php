#!/usr/bin/env php
<?php
/**

title=taskModel->sendMailForRelationTask();
timeout=0
cid=1

- 测试给已开始的任务1发送邮件 @1
- 测试给已完成的任务1发送邮件 @1
- 测试给已开始的任务2发送邮件 @1
- 测试给已完成的任务2发送邮件 @1
- 测试给已开始的任务3发送邮件 @1
- 测试给已完成的任务3发送邮件 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(3);
zenData('task')->loadYaml('task')->gen(3);
zenData('action')->gen(5);
zenData('relationoftasks')->gen(0);

$taskIDList = range(1, 6);
$taskTester = new taskModelTest();
r($taskTester->sendMailForRelationTaskTest($taskIDList[0], 'started'))  && p() && e('1'); // 测试给已开始的任务1发送邮件
r($taskTester->sendMailForRelationTaskTest($taskIDList[0], 'finished')) && p() && e('1'); // 测试给已完成的任务1发送邮件
r($taskTester->sendMailForRelationTaskTest($taskIDList[1], 'started'))  && p() && e('1'); // 测试给已开始的任务2发送邮件
r($taskTester->sendMailForRelationTaskTest($taskIDList[1], 'finished')) && p() && e('1'); // 测试给已完成的任务2发送邮件
r($taskTester->sendMailForRelationTaskTest($taskIDList[2], 'started'))  && p() && e('1'); // 测试给已开始的任务3发送邮件
r($taskTester->sendMailForRelationTaskTest($taskIDList[2], 'finished')) && p() && e('1'); // 测试给已完成的任务3发送邮件
