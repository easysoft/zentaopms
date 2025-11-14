#!/usr/bin/env php
<?php
/**

title=测试 installModel->getLicense();
timeout=0
cid=16773

- 获取中文授权内容。 @Z PUBLIC LICENSE
- 获取英文授权内容。 @The source code of zentao is covered by the fol
- 获取德语授权内容。 @The source code of zentao is covered by the fol
- 获取法语授权内容。 @The source code of zentao is covered by the fol
- 获取繁体授权内容。 @Z PUBLIC LICENSE

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $app;
$tester->loadModel('install');

$app->clientLang = 'zh-cn';
r(substr($tester->install->getLicense(), 25, 16)) && p() && e('Z PUBLIC LICENSE');           // 获取中文授权内容。

$app->clientLang = 'en';
r(substr($tester->install->getLicense(), 0, 47)) && p() && e('The source code of zentao is covered by the fol'); // 获取英文授权内容。

$app->clientLang = 'de';
r(substr($tester->install->getLicense(), 0, 47)) && p() && e('The source code of zentao is covered by the fol'); // 获取德语授权内容。

$app->clientLang = 'fr';
r(substr($tester->install->getLicense(), 0, 47)) && p() && e('The source code of zentao is covered by the fol'); // 获取法语授权内容。

$app->clientLang = 'zh-tw';
r(substr($tester->install->getLicense(), 25, 16)) && p() && e('Z PUBLIC LICENSE'); // 获取繁体授权内容。
