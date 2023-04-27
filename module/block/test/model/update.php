#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

function initData()
{
    $block = zdTable('block');
    $block->id->range('2');
    $block->account->range('test');
    $block->vision->range('rnd');
    $block->module->range('test');
    $block->title->prefix('区块')->range('2');
    $block->source->range('my');
    $block->block->range('bug');
    $block->order->range('5');

    $block->gen(1);
}

/**

title=14:11:23 ERROR: SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry &
timeout=0
cid=39

- 执行block模块的getByID方法，参数是$newBlockID
 - 属性account @admin
 - 属性vision @rnd
 - 属性module @my
 - 属性title @区块123
 - 属性source @my
 - 属性block @bug
 - 属性order @5

- 执行blockTest模块的create方法，参数是$accoutTooLangBlock,属性account @『所属用户』长度应当不超过『30』，且大于『0』。



*/

global $tester;
$tester->loadModel('block');

initData();

$newBlock = new stdclass();
$newBlock->id      = '2';
$newBlock->account = 'newadmin';
$newBlock->vision  = 'lite';
$newBlock->module  = 'newmy';
$newBlock->title   = 'new区块';
$newBlock->source  = 'newmy';
$newBlock->params  = '';
$newBlock->block   = 'newbug';
$newBlock->order   = '5';

$accoutTooLangBlock = new stdclass();
$accoutTooLangBlock->id      = '2';
$accoutTooLangBlock->account = 'adminadminadminadminadminadminadminadmin1';
$accoutTooLangBlock->vision  = 'rnd';
$accoutTooLangBlock->module  = 'my';
$accoutTooLangBlock->title   = 'long account';
$accoutTooLangBlock->source  = 'my';
$accoutTooLangBlock->block   = 'bug';
$accoutTooLangBlock->order   = '5';

$blockTest = new blockTest();
$newBlockID = $blockTest->updateTest($newBlock);

r($tester->block->getByID($newBlockID)) && p('account,vision,module,title,source,block,order') && e("{$newBlock->account},{$newBlock->vision},{$newBlock->module},{$newBlock->title},{$newBlock->source},{$newBlock->block},{$newBlock->order}"); // 测试获取正常的block的内容
r($blockTest->updateTest($accoutTooLangBlock)) && p('account:0') && e('『所属用户』长度应当不超过『30』，且大于『0』。'); // 测试account 字段字符超出长度
