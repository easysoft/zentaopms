#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

function initData()
{
    $block = zdTable('block');
    $block->id->range('2-5');
    $block->account->range('admin');
    $block->vision->range('rnd,lite');
    $block->dashboard->range('my');
    $block->module->range('project');
    $block->code->range('list,statistic');
    $block->title->prefix('区块名称')->range('2-5');
    $block->order->range('1-4');

    $block->gen(4);
}

/**

title=测试 block 模块的update 方法
timeout=0
cid=39

- 测试获取正常的block的内容
 - 属性account @admin2
 - 属性vision @lite
 - 属性dashboard @scrumproject
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

initData();

$block = new stdclass();
$block->id        = '2';
$block->account   = 'admin2';
$block->vision    = 'lite';
$block->dashboard = 'scrumproject';
$block->module    = 'project';
$block->code      = 'statistic';
$block->title     = '项目统计区块';
$block->order     = '1';

$accountTooLangBlock = new stdclass();
$accountTooLangBlock->id        = '3';
$accountTooLangBlock->account   = 'adminadminadminadminadminadminadminadmin1';
$accountTooLangBlock->vision    = 'rnd';
$accountTooLangBlock->dashboard = 'my';
$accountTooLangBlock->module    = 'project';
$accountTooLangBlock->code      = 'statistic';
$accountTooLangBlock->title     = '项目统计区块2';
$accountTooLangBlock->order     = '2';

$emptyModuleBlock = new stdclass();
$emptyModuleBlock->id     = '4';
$emptyModuleBlock->module = '';

$emptyCodeBlock = new stdclass();
$emptyCodeBlock->id   = '4';
$emptyCodeBlock->code = '';

$emptyTitleBlock = new stdclass();
$emptyTitleBlock->id    = '4';
$emptyTitleBlock->title = '';

$blockTest = new blockTest();
$newBlockID = $blockTest->updateTest($block);

r($tester->block->getByID($newBlockID)) && p('account,vision,dashboard,module,code,title,order') && e('admin2,lite,scrumproject,project,statistic,项目统计区块,1'); // 测试获取正常的block的内容
r($blockTest->updateTest($emptyModuleBlock)) && p('module:0') && e('『所属模块』不能为空。'); // 测试所属模块为空
r($blockTest->updateTest($emptyCodeBlock)) && p('code:0') && e('『区块』不能为空。'); // 测试所属区块为空
r($blockTest->updateTest($emptyTitleBlock)) && p('title:0') && e('『区块名称』不能为空。'); // 测试区块名称为空
r($blockTest->updateTest($accountTooLangBlock)) && p('account:0') && e('『所属用户』长度应当不超过『30』，且大于『0』。'); // 测试account 字段字符超出长度