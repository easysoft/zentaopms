#!/usr/bin/env php
<?php
/**

title=测试 installModel->getLicense();
timeout=0
cid=1

- 获取中文授权内容。 @禅道项目管理软件使用 Z PUBLIC LICENSE
- 获取英文授权内容。 @The source code of zentao is covered by the fol

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $app;
$tester->loadModel('install');

$app->clientLang = 'zh-cn';
r(substr($tester->install->getLicense(), 0, 47)) && p() && e('禅道项目管理软件使用 Z PUBLIC LICENSE');           // 获取中文授权内容。

$app->clientLang = 'en';
r(substr($tester->install->getLicense(), 0, 47)) && p() && e('The source code of zentao is covered by the fol'); // 获取英文授权内容。
