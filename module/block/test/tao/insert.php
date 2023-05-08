#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

/**

title=测试 block 模块 tao下的 insert 方法
timeout=0
cid=39

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

r($tester->block->insert($block)) && p('') && e('1'); // 测试正常情况

$tester->block->insert($accountTooLangBlock);
r(dao::getError()) && p('account:0') && e('『所属用户』长度应当不超过『30』，且大于『0』。'); // 测试account 字段字符超出长度

$tester->block->insert($emptyModuleBlock);
r(dao::getError()) && p('module:0') && e('『所属模块』不能为空。'); // 测试所属模块为空

$tester->block->insert($emptyCodeBlock);
r(dao::getError()) && p('code:0') && e('『区块』不能为空。'); // 测试所属区块为空

$tester->block->insert($emptyTitleBlock);
r(dao::getError()) && p('title:0') && e('『区块名称』不能为空。'); // 测试区块名称为空
