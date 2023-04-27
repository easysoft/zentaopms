#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

function initData()
{
    $block = zdTable('block');
    $block->id->range('2-5');
    $block->account->range('admin,test1,test2');
    $block->vision->range('rnd,lite');
    $block->module->range('my,qa,project');
    $block->title->prefix('区块')->range('2-5');
    $block->source->range('my,qa,project');
    $block->block->range('bug,case,story');
    $block->order->range('5-2');

    $block->gen(4);
}

/**

title=测试 blockModel->getByID();
timeout=0
cid=1

- 执行block模块的getByID方法，参数是2
 - 属性account @admin
 - 属性vision @rnd
 - 属性module @my
 - 属性title @区块2
 - 属性source @my
 - 属性block @bug
 - 属性order @5

- 执行block模块的getByID方法，参数是4,属性order @3
- 执行block模块的getByID方法，参数是6 @0


*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getByID(2)) && p('account,vision,module,title,source,block,order') && e('admin,rnd,my,区块2,my,bug,5');        // 测试获取正常的block的内容
r($tester->block->getByID(4)) && p('order') && e('3');        // 测试获取正常的block的内容
r($tester->block->getByID(6)) && p('') && e('0');        // 测试获取不存在的block的内容
