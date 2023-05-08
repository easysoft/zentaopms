#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

/**

title=测试 block 模块 model下的 create 方法
timeout=0
cid=39

- 测试获取正常的block的内容
 - 属性account @admin
 - 属性vision @rnd
 - 属性dashboard @my
 - 属性module @project
 - 属性code @statistic
 - 属性title @项目统计区块
 - 属性order @1

- 测试所属模块为空 @『所属模块』不能为空。

- 测试所属区块为空 @『区块』不能为空。

- 测试区块名称为空 @『区块名称』不能为空。

- 测试account 字段字符超出长度 @『所属用户』长度应当不超过『30』，且大于『0』。

*/

global $tester;
$tester->loadModel('block');

$block = new stdclass();
$block->account   = 'admin';
$block->vision    = 'rnd';
$block->dashboard = 'my';
$block->module    = 'project';
$block->code      = 'statistic';
$block->title     = '项目统计区块';
$block->order     = '1';

$accountTooLangBlock = new stdclass();
$accountTooLangBlock->account   = 'adminadminadminadminadminadminadminadmin1';
$accountTooLangBlock->vision    = 'rnd';
$accountTooLangBlock->dashboard = 'my';
$accountTooLangBlock->module    = 'project';
$accountTooLangBlock->code      = 'statistic';
$accountTooLangBlock->title     = '项目统计区块2';
$accountTooLangBlock->order     = '2';

$emptyModuleBlock = new stdclass();
$emptyModuleBlock->module  = '';

$emptyCodeBlock = new stdclass();
$emptyCodeBlock->code = '';

$emptyTitleBlock = new stdclass();
$emptyTitleBlock->title  = '';

$blockTest = new blockTest();
$newBlockID = $blockTest->createTest($block);

r($tester->block->getByID($newBlockID)) && p('account,vision,dashboard,module,code,title,order') && e('admin,rnd,my,project,statistic,项目统计区块,1'); // 测试获取正常的block的内容
r($blockTest->createTest($emptyModuleBlock)) && p('module:0') && e('『所属模块』不能为空。'); // 测试所属模块为空
r($blockTest->createTest($emptyCodeBlock)) && p('code:0') && e('『区块』不能为空。'); // 测试所属区块为空
r($blockTest->createTest($emptyTitleBlock)) && p('title:0') && e('『区块名称』不能为空。'); // 测试区块名称为空
r($blockTest->createTest($accountTooLangBlock)) && p('account:0') && e('『所属用户』长度应当不超过『30』，且大于『0』。'); // 测试account 字段字符超出长度