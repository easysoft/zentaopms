#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/sendmail.ui.class.php';

/**

title=需求邮件发送测试
timeout=0
cid=1

- 需求邮件模板内容验证
 - 最终测试状态 @SUCCESS
 - 测试结果 @需求邮件模板内容验证成功
- 需求邮件链接功能验证
 - 最终测试状态 @SUCCESS
 - 测试结果 @需求邮件链接功能验证成功
- mail model邮件内容验证
 - 最终测试状态 @SUCCESS
 - 测试结果 @mail model邮件内容验证成功

*/

$story = new stdClass();
$story->id = 1;
$story->title = 'SendmailTest';
$story->spec = '这是需求的详细描述';
$story->color = '#333';

$tester = new SendmailTester();

r($tester->testTemplateContent($story))   && p('status,message') && e('SUCCESS,需求邮件模板内容验证成功');   // 需求邮件模板内容验证
r($tester->testLinkFunctionality($story)) && p('status,message') && e('SUCCESS,需求邮件链接功能验证成功');   // 需求邮件链接功能验证
r($tester->testIntegrationMail($story))   && p('status,message') && e('SUCCESS,mail model邮件内容验证成功'); // mail model邮件内容验证

$tester->closeBrowser();