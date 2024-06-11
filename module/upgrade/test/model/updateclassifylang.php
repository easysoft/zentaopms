#!/usr/bin/env php
<?php
/**

title=测试 upgradeModel->updateClassifyLang();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$langData = zenData('lang');
$langData->key->range('support,engineering,project');
$langData->gen(6);

global $tester, $app;
$tester->loadModel('upgrade');
$tester->loadModel('install');

$app->clientLang = 'zh-cn';
$app->loadLang('install');
$tester->upgrade->updateClassifyLang();
r($tester->install->fetchByID(1, 'lang')) && p('id,value') && e('1,支持过程'); // 检查lang表的语言项是否变更成对应的中文语言项。

$app::$loadedLangs = array();
$app->clientLang = 'en';
$app->loadLang('install');
$tester->upgrade->updateClassifyLang();
r($tester->install->fetchByID(1, 'lang')) && p('id,value') && e('1,Support Process'); // 检查lang表的语言项是否变更成对应的英文语言项。
