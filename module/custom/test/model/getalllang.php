#!/usr/bin/env php
<?php
/**

title=测试 customModel->getAllLang();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('lang')->config('lang')->gen(10);
zdTable('user')->gen(5);
su('admin');

$customTester = new customTest();
$allLang      = $customTester->getAllLangTest();

r($allLang[6])  && p('key,value') && e('executionCommon,执行'); // 获取自定义迭代概念
r($allLang[7])  && p('key,value') && e('my,地盘1');             // 获取自定义地盘导航语言项
r($allLang[8])  && p('key,value') && e('index,仪表盘1');        // 获取自定义地盘-仪表盘导航语言项
r($allLang[9])  && p('key,value') && e('task,任务1');           // 获取自定义地盘-待处理-任务导航语言项
r($allLang[10]) && p('key,value') && e('all,指派给我1');        // 获取自定义地盘-待处理-任务列表，指派给标签语言项

zdTable('lang')->config('lang')->gen(0);
