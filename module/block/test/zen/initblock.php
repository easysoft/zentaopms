#!/usr/bin/env php
<?php

/**

title=测试 blockZen::initBlock();
timeout=0
cid=15244

- 执行blockTest模块的zenInitBlockTest方法，参数是'my'  @1
- 执行blockTest模块的zenInitBlockTest方法，参数是'qa'  @1
- 执行blockTest模块的zenInitBlockTest方法，参数是''  @0
- 执行blockTest模块的zenInitBlockTest方法，参数是'product'  @1
- 执行blockTest模块的zenInitBlockTest方法，参数是'my'  @1
- 步骤6：GitFox独立版默认展示6个区块 @6
- 步骤7：GitFox独立版第1个区块是欢迎总览 @welcome|welcome
- 步骤8：GitFox独立版第2个区块是待我评审 @ppm|reviewbyme
- 步骤9：GitFox独立版第3个区块是我创建的评审 @ppm|reviewcreatedbyme
- 步骤10：GitFox独立版第4个区块是GitFox动态 @zentaodynamic|zentaodynamic
- 步骤11：GitFox独立版第5个区块是最新动态 @dynamic|dynamic
- 步骤12：GitFox独立版第6个区块是团队成就 @teamachievement|teamachievement
- 步骤13：GitFox独立版左侧三个区块都是长区块 @6
- 步骤14：GitFox独立版右侧三个区块都是短区块 @3
- 步骤15：GitFox独立版团队成就区块高度为4 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$blockTest = new blockZenTest();

r($blockTest->zenInitBlockTest('my')) && p() && e(1);
r($blockTest->zenInitBlockTest('qa')) && p() && e(1);
r($blockTest->zenInitBlockTest('')) && p() && e(0);
r($blockTest->zenInitBlockTest('product')) && p() && e(1);
r($blockTest->zenInitBlockTest('my')) && p() && e(1);

/* GitFox独立版默认展示的区块：左侧是欢迎总览、待我评审、我创建的评审长区块，右侧是GitFox动态、最新动态、团队成就短区块。 */
$config->edition = 'gitfox';
$config->vision  = 'gitfox';

$tester->app->loadLang('block');
$extLangPath = $tester->app->getModuleExtPath('block', 'lang');
include $extLangPath['common'] . 'zh-cn/gitfox.php';

$extConfigPath = $tester->app->getModuleExtPath('block', 'config');
include $extConfigPath['common'] . 'gitfox.php';

$account = $tester->app->user->account;
$tester->dao->delete()->from(TABLE_BLOCK)->where('account')->eq($account)->andWhere('dashboard')->eq('my')->exec();
$blockTest->zenInitBlockTest('my');

$gitfoxBlocks = $tester->dao->select('module,code,width,height')->from(TABLE_BLOCK)->where('account')->eq($account)->andWhere('dashboard')->eq('my')->orderBy('id')->fetchAll();
$gitfoxBlockList = array();
foreach($gitfoxBlocks as $gitfoxBlock) $gitfoxBlockList[] = $gitfoxBlock->module . '|' . $gitfoxBlock->code;

r(count($gitfoxBlocks)) && p() && e('6'); // 步骤6：默认展示6个区块
r($gitfoxBlockList[0]) && p() && e('welcome|welcome'); // 步骤7：第1个区块是欢迎总览
r($gitfoxBlockList[1]) && p() && e('ppm|reviewbyme'); // 步骤8：第2个区块是待我评审
r($gitfoxBlockList[2]) && p() && e('ppm|reviewcreatedbyme'); // 步骤9：第3个区块是我创建的评审
r($gitfoxBlockList[3]) && p() && e('zentaodynamic|zentaodynamic'); // 步骤10：第4个区块是GitFox动态，复用禅道动态区块的实现
r($gitfoxBlockList[4]) && p() && e('dynamic|dynamic'); // 步骤11：第5个区块是最新动态
r($gitfoxBlockList[5]) && p() && e('teamachievement|teamachievement'); // 步骤12：第6个区块是团队成就
r((int)$gitfoxBlocks[0]->width + (int)$gitfoxBlocks[1]->width + (int)$gitfoxBlocks[2]->width) && p() && e('6'); // 步骤13：左侧都是长区块
r((int)$gitfoxBlocks[3]->width + (int)$gitfoxBlocks[4]->width + (int)$gitfoxBlocks[5]->width) && p() && e('3'); // 步骤14：右侧都是短区块
r((int)$gitfoxBlocks[5]->height) && p() && e('4'); // 步骤15：团队成就区块高度为4
