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
    $block->order->range('5');

    $block->gen(1);
}

/**

title=测试 block 模块 model下的 create 方法
timeout=0
cid=39

- 执行block模块的getByID方法，参数是$newBlockID
 - 属性account @admin
 - 属性vision @rnd
 - 属性dashboard @my
 - 属性title @区块123
 - 属性source @my
 - 属性block @bug
 - 属性order @5

- 执行blockTest模块的create方法，参数是$accoutTooLangBlock,属性account @『所属用户』长度应当不超过『30』，且大于『0』。



*/

global $tester;
$tester->loadModel('block');

initData();

$block = new stdclass();
$block->account   = 'admin';
$block->vision    = 'rnd';
$block->dashboard = 'my';
$block->title     = '区块123';
$block->order     = '5';

$accoutTooLangBlock = new stdclass();
$accoutTooLangBlock->account   = 'adminadminadminadminadminadminadminadmin1';
$accoutTooLangBlock->vision    = 'rnd';
$accoutTooLangBlock->dashboard = 'my';
$accoutTooLangBlock->title     = 'long account';
$accoutTooLangBlock->order     = '5';

$blockTest = new blockTest();
$newBlockID = $blockTest->createTest($block);

r($tester->block->getByID($newBlockID)) && p('account,vision,dashboard,title,order') && e('admin,rnd,my,区块123,5'); // 测试获取正常的block的内容
r($blockTest->createTest($accoutTooLangBlock)) && p('account:0') && e('『所属用户』长度应当不超过『30』，且大于『0』。'); // 测试account 字段字符超出长度
