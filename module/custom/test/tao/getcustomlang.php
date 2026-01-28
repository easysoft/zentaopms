#!/usr/bin/env php
<?php
/**

title=测试 customModel->getCustomLang();
timeout=0
cid=15929

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('user')->gen(5);
su('admin');

$customTester = new customTaoTest();
$allCustomLang = $customTester->getCustomLangTest();

r($allCustomLang[6])  && p('key,value') && e('executionCommon,执行'); // 获取自定义迭代概念
r($allCustomLang[7])  && p('key,value') && e('my,地盘1');             // 获取自定义地盘导航语言项
r($allCustomLang[8])  && p('key,value') && e('index,仪表盘1');        // 获取自定义地盘-仪表盘导航语言项
r($allCustomLang[9])  && p('key,value') && e('task,任务1');           // 获取自定义地盘-待处理-任务导航语言项
r($allCustomLang[10]) && p('key,value') && e('all,指派给我1');        // 获取自定义地盘-待处理-任务列表，指派给标签语言项

zenData('lang')->loadYaml('lang')->gen(0);
