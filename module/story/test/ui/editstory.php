#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=编辑研发需求测试
timeout=0
cid=80

- 成功 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editstory.ui.class.php';

$tester = new editStoryTester();
$tester->login();

$storyFrom = '客户';

r($tester->editStory($storyFrom))    && p('message,status') && e('创建需求页面名称为空提示正确,SUCCESS'); // 缺少需求名称，创建失败

$tester->closeBrowser();
