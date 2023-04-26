#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

function initData()
{
    $block = zdTable('block');
    $block->gen(1);
}

/**

title=18:41:52 ERROR: SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry &
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

- 执行blockTest模块的create方法，参数是$accoutTooLangBlock @


*/

global $tester;
$tester->loadModel('block');

initData();

$block = new stdclass();
$block->account = 'admin';
$block->vision  = 'rnd';
$block->module  = 'my';
$block->title   = '区块123';
$block->source  = 'my';
$block->block   = 'bug';
$block->order   = '5';

$repeatBlock = new stdclass();
$repeatBlock->account = 'admin';
$repeatBlock->vision  = 'rnd';
$repeatBlock->module  = 'my';
$repeatBlock->title   = '区块123';
$repeatBlock->source  = 'my';
$repeatBlock->block   = 'bug';
$repeatBlock->order   = '5';

$accoutTooLangBlock = new stdclass();
$accoutTooLangBlock->account = 'adminadminadminadminadminadmin1';
$accoutTooLangBlock->vision  = 'rnd';
$accoutTooLangBlock->module  = 'my';
$accoutTooLangBlock->title   = '区块123';
$accoutTooLangBlock->source  = 'my';
$accoutTooLangBlock->block   = 'bug';
$accoutTooLangBlock->order   = '5';

$titleTooLangBlock = new stdclass();
$titleTooLangBlock->account = 'admin';
$titleTooLangBlock->vision  = 'rnd';
$titleTooLangBlock->module  = 'my';
$titleTooLangBlock->title   = '5个字区块5个字区块5个字区块5个字区块5个字区块1';
$titleTooLangBlock->source  = 'my';
$titleTooLangBlock->block   = 'bug';
$titleTooLangBlock->order   = '5';
$blockTest = new blockTest();

$newBlockID = $blockTest->createTest($block);

r($tester->block->getByID($newBlockID)) && p('account,vision,module,title,source,block,order') && e('admin,rnd,my,区块123,my,bug,5'); // 测试获取正常的block的内容
r($blockTest->createTest($repeatBlock)) && p('') && e(''); // 测试联合主键不能重复
r($blockTest->createTest($accoutTooLangBlock)) && p('') && e(''); // 测试account 字段字符超出长度





