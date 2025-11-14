#!/usr/bin/env php
<?php

/**

title=测试 upgradeModel->completeClassifyLang();
timeout=0
cid=19503

- 检查数据库中已经给融合敏捷模型添加了project分类项 @项目管理
- 检查数据库中已经给融合敏捷模型添加了support分类项 @支持过程
- 检查数据库中已经给融合敏捷模型添加了engineering分类项 @工程支持
- 检查数据库中敏捷模型未添加project分类项 @项目管理
- 检查数据库中已经给融合瀑布模型添加了support,engineering，没添加project分类项 @项目管理

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';
su('admin');

$langData = zenData('lang')->loadYaml('lang_completeclassifylang')->gen(8);

global $tester, $app;

$upgrade = new upgradeTest();

r($upgrade->completeClassifyLangTest('agileplusClassify', 'project'))     && p() && e('项目管理'); // 检查数据库中已经给融合敏捷模型添加了project分类项
r($upgrade->completeClassifyLangTest('agileplusClassify', 'support'))     && p() && e('支持过程'); // 检查数据库中已经给融合敏捷模型添加了support分类项
r($upgrade->completeClassifyLangTest('agileplusClassify', 'engineering')) && p() && e('工程支持'); // 检查数据库中已经给融合敏捷模型添加了engineering分类项
r($upgrade->completeClassifyLangTest('scrumClassify', 'project'))         && p() && e('项目管理'); // 检查数据库中敏捷模型未添加project分类项
r($upgrade->completeClassifyLangTest('waterfallplusClassify', 'project')) && p() && e('项目管理'); // 检查数据库中已经给融合瀑布模型添加了support,engineering，没添加project分类项
