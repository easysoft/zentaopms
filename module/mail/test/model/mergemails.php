#!/usr/bin/env php
<?php

/**

title=测试 mailModel->mergeMails();
timeout=0
cid=17015

- 测试步骤1：不传入任何数据 @0
- 测试步骤2：只传入1条数据
 - 属性id @1
 - 属性subject @主题1
 - 属性data @用户创建了任务1
- 测试步骤3：传入2条数据合并
 - 属性id @1,2
 - 属性subject @主题1|主题2
- 测试步骤4：传入多条数据合并属性subject @主题1|主题2|更多...
- 测试步骤5：验证合并邮件的HTML处理属性data @<table></table>用户创建了任务1用户创建了任务2</td>

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->mergeMailsTest(array())) && p() && e('0'); //测试步骤1：不传入任何数据
r($mailTest->mergeMailsTest(array((object)array('id' => 1, 'subject' => '主题1', 'data' => '用户创建了任务1', 'toList' => 'admin', 'ccList' => '')))) && p('id,subject,data') && e('1,主题1,用户创建了任务1'); //测试步骤2：只传入1条数据
r($mailTest->mergeMailsTest(array((object)array('id' => 1, 'subject' => '主题1', 'data' => '<table></table>用户创建了任务1</td>', 'toList' => 'admin', 'ccList' => ''), (object)array('id' => 2, 'subject' => '主题2', 'data' => '<table></table>用户创建了任务2</td>', 'toList' => 'admin', 'ccList' => '')))) && p('id;subject', ';') && e('1,2;主题1|主题2'); //测试步骤3：传入2条数据合并
r($mailTest->mergeMailsTest(array((object)array('id' => 1, 'subject' => '主题1', 'data' => '<table></table>用户创建了任务1</td>', 'toList' => 'admin', 'ccList' => ''), (object)array('id' => 2, 'subject' => '主题2', 'data' => '<table></table>用户创建了任务2</td>', 'toList' => 'admin', 'ccList' => ''), (object)array('id' => 3, 'subject' => '主题3', 'data' => '<table></table>用户创建了任务3</td>', 'toList' => 'admin', 'ccList' => '')))) && p('subject') && e('主题1|主题2|更多...'); //测试步骤4：传入多条数据合并
r($mailTest->mergeMailsTest(array((object)array('id' => 1, 'subject' => '主题1', 'data' => '<table></table>用户创建了任务1</td>', 'toList' => 'admin', 'ccList' => ''), (object)array('id' => 2, 'subject' => '主题2', 'data' => '<table></table>用户创建了任务2</td>', 'toList' => 'admin', 'ccList' => '')))) && p('data') && e('<table></table>用户创建了任务1用户创建了任务2</td>'); //测试步骤5：验证合并邮件的HTML处理