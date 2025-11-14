#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setSubject();
timeout=0
cid=17025

- 执行mail模块的setSubjectTest方法，参数是'Normal subject text' 属性Subject @Normal subject text
- 执行mail模块的setSubjectTest方法，参数是'Test with \\slash' 属性Subject @Test with slash
- 执行mail模块的setSubjectTest方法，参数是'' 属性Subject @~~
- 执行mail模块的setSubjectTest方法，参数是'Test \\\"escaped quotes\\\"' 属性Subject @Test \"escaped quotes\"
- 执行mail模块的setSubjectTest方法，参数是'<b>HTML</b> tags & entities' 属性Subject @<b>HTML</b> tags & entities
- 执行mail模块的setSubjectTest方法，参数是'This is a very long subject line that contains multiple words and should be handled properly by the setSubject method without any issues' 属性Subject @This is a very long subject line that contains multiple words and should be handled properly by the setSubject method without any issues
- 执行mail模块的setSubjectTest方法，参数是'中文主题测试：包含中文字符的邮件主题' 属性Subject @中文主题测试：包含中文字符的邮件主题

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mail = new mailTest();

r($mail->setSubjectTest('Normal subject text')) && p('Subject') && e('Normal subject text');
r($mail->setSubjectTest('Test with \\slash')) && p('Subject') && e('Test with slash');
r($mail->setSubjectTest('')) && p('Subject') && e('~~');
r($mail->setSubjectTest('Test \\\"escaped quotes\\\"')) && p('Subject') && e('Test \"escaped quotes\"');
r($mail->setSubjectTest('<b>HTML</b> tags & entities')) && p('Subject') && e('<b>HTML</b> tags & entities');
r($mail->setSubjectTest('This is a very long subject line that contains multiple words and should be handled properly by the setSubject method without any issues')) && p('Subject') && e('This is a very long subject line that contains multiple words and should be handled properly by the setSubject method without any issues');
r($mail->setSubjectTest('中文主题测试：包含中文字符的邮件主题')) && p('Subject') && e('中文主题测试：包含中文字符的邮件主题');