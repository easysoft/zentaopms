#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $block = zenData('block');
    $block->id->range('2-5');
    $block->account->range('admin');
    $block->dashboard->range('my');
    $block->module->range('welcome,guide,project,project');
    $block->code->range('welcome,guide,list,statistic');
    $block->title->prefix('区块名称')->range('1-4');
    $block->width->range('2,2,1,1');

    $block->gen(4);
}

/**

title=测试 blockModel->getByID();
timeout=0
cid=15229

- 测试获取ID为2的block的内容
 - 属性account @admin
 - 属性dashboard @my
 - 属性module @welcome
 - 属性code @welcome
 - 属性title @区块名称1
 - 属性width @2

- 测试获取ID为3的block的内容
 - 属性account @admin
 - 属性dashboard @my
 - 属性module @guide
 - 属性code @guide
 - 属性title @区块名称2
 - 属性width @2

- 测试获取ID不存在的block的内容 @0

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getByID(2)) && p('account,dashboard,module,code,title,width') && e('admin,my,welcome,welcome,区块名称1,2'); // 测试获取ID为2的block的内容
r($tester->block->getByID(3)) && p('account,dashboard,module,code,title,width') && e('admin,my,guide,guide,区块名称2,2');     // 测试获取ID为3的block的内容
r($tester->block->getByID(6)) && p('') && e('0'); // 测试获取ID不存在的block的内容
