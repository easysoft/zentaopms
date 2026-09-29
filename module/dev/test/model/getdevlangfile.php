#!/usr/bin/env php
<?php

/**

title=测试 devModel::getDevLangFile();
timeout=0
cid=0

- 步骤1：中文语言文件路径 @yes
- 步骤2：英文语言文件路径 @yes
- 步骤3：语言文件真实存在 @yes
- 步骤4：中文繁体语言文件路径 @yes
- 步骤5：不存在的语言返回空 @0
- 步骤6：语言文件扩展名为 php @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('task')->gen(1);

$dev = new devModelTest();

r(strpos($dev->getDevLangFileTest('zh-cn'), 'dev/lang/zh-cn.php') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：中文语言文件路径
r(strpos($dev->getDevLangFileTest('en'), 'dev/lang/en.php') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤2：英文语言文件路径
r(file_exists($dev->getDevLangFileTest('zh-cn')) ? 'yes' : 'fail') && p() && e('yes'); // 步骤3：语言文件真实存在
r(strpos($dev->getDevLangFileTest('zh-tw'), 'dev/lang/zh-tw.php') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤4：中文繁体语言文件路径
r($dev->getDevLangFileTest('no-such-lang')) && p() && e('0'); // 步骤5：不存在的语言返回空
r(substr($dev->getDevLangFileTest('zh-cn'), -4) == '.php' ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：语言文件扩展名为 php
