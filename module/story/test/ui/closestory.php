#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=关闭研发需求测试
timeout=0
cid=88
- 关闭没有父需求的研发需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 关闭有父需求的研发需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 关闭需求成功，最终测试状态 @success
 */
chdir (__DIR__);
include '../lib/closestory.ui.class.php';

$tester = new closeStoryTester();
$tester->login();

$closeReason = array('已完成', '不做');

r($tester->closeStory(3, $closeReason[0])) && p('message,status') && e('关闭需求成功，SUCCESS');

$tester->closeBrowser();
