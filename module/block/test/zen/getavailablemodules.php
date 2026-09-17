#!/usr/bin/env php
<?php

/**

title=测试 blockZen::getAvailableModules();
timeout=0
cid=15240

- 执行blockTest模块的getAvailableModulesTest方法，参数是'my'  @7
- 执行blockTest模块的getAvailableModulesTest方法，参数是'product'  @0
- 执行blockTest模块的getAvailableModulesTest方法，参数是'project'  @0
- 执行blockTest模块的getAvailableModulesTest方法，参数是'execution'  @0
- 执行blockTest模块的getAvailableModulesTest方法，参数是'qa'  @0
- 步骤6：GitFox独立版不展示产品模块 @0
- 步骤7：GitFox独立版不展示项目模块 @0
- 步骤8：GitFox独立版不展示执行模块 @0
- 步骤9：GitFox独立版不展示测试模块 @0
- 步骤10：GitFox独立版不展示使用帮助模块 @0
- 步骤11：禅道动态区块改名为GitFox动态 @GitFox动态
- 步骤12：GitFox独立版展示欢迎总览模块 @1
- 步骤13：GitFox独立版评审模块展示在欢迎总览之前 @ppm|welcome

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$blockTest = new blockZenTest();

r(count($blockTest->getAvailableModulesTest('my'))) && p() && e('7');
r(count($blockTest->getAvailableModulesTest('product'))) && p() && e('0');
r(count($blockTest->getAvailableModulesTest('project'))) && p() && e('0');
r(count($blockTest->getAvailableModulesTest('execution'))) && p() && e('0');
r(count($blockTest->getAvailableModulesTest('qa'))) && p() && e('0');

/* GitFox独立版本不存在产品、项目、执行、测试、使用帮助等功能，编辑区块时只展示下面的模块菜单，禅道动态区块改名为GitFox动态。 */
$config->edition = 'gitfox';
$config->vision  = 'gitfox';

$tester->app->loadLang('block');
$extLangPath = $tester->app->getModuleExtPath('block', 'lang');
include $extLangPath['common'] . 'zh-cn/gitfox.php';

$gitfoxModules = $blockTest->getAvailableModulesTest('my');

r(isset($gitfoxModules['product'])       ? 1 : 0) && p() && e('0'); // 步骤6：不展示产品模块
r(isset($gitfoxModules['project'])       ? 1 : 0) && p() && e('0'); // 步骤7：不展示项目模块
r(isset($gitfoxModules['execution'])     ? 1 : 0) && p() && e('0'); // 步骤8：不展示执行模块
r(isset($gitfoxModules['qa'])            ? 1 : 0) && p() && e('0'); // 步骤9：不展示测试模块
r(isset($gitfoxModules['guide'])         ? 1 : 0) && p() && e('0'); // 步骤10：不展示使用帮助模块
r(isset($gitfoxModules['zentaodynamic']) ? $gitfoxModules['zentaodynamic'] : '') && p() && e('GitFox动态'); // 步骤11：禅道动态区块改名为GitFox动态
r(isset($gitfoxModules['welcome'])       ? 1 : 0) && p() && e('1'); // 步骤12：展示欢迎总览模块

$gitfoxModuleKeys = array_keys($gitfoxModules);
r($gitfoxModuleKeys[0] . '|' . $gitfoxModuleKeys[1]) && p() && e('ppm|welcome'); // 步骤13：评审模块展示在欢迎总览之前
