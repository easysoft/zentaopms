#!/usr/bin/env php
<?php

/**

title=创建项目向导
timeout=0
cid=1

- 检查scrum向导跳转测试结果 @scrum向导跳转正确
- 检查waterfall向导跳转测试结果 @waterfall向导跳转正确
- 检查kanban向导跳转测试结果 @kanban向导跳转正确
- 检查agileplus向导跳转测试结果 @agileplus向导跳转正确
- 检查waterfallplus向导跳转测试结果 @waterfallplus向导跳转正确

*/

chdir(__DIR__);
include '../lib/createguide.ui.class.php';

$tester = new createGuideTester();
$tester->login();

r($tester->createGuide('scrum'))         && p('message') && e('scrum向导跳转正确');         // 检查scrum向导跳转
r($tester->createGuide('waterfall'))     && p('message') && e('waterfall向导跳转正确');     // 检查waterfall向导跳转
r($tester->createGuide('kanban'))        && p('message') && e('kanban向导跳转正确');        // 检查kanban向导跳转
r($tester->createGuide('agileplus'))     && p('message') && e('agileplus向导跳转正确');     // 检查agileplus向导跳转
r($tester->createGuide('waterfallplus')) && p('message') && e('waterfallplus向导跳转正确'); // 检查waterfallplus向导跳转

$tester->closeBrowser();
