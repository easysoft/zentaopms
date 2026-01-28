#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

title=测试 block 模块的update 方法
timeout=0
cid=15235

- 测试获取正常的block的内容
 - 属性account @admin
 - 属性dashboard @my
 - 属性module @project
 - 属性code @statistic
 - 属性title @项目统计区块
 - 属性vision @rnd
 - 属性width @1
 - 属性height @3
- 测试所属模块为空第module条的0属性 @『所属模块』不能为空。
- 测试所属区块为空第code条的0属性 @『区块』不能为空。
- 测试区块名称为空第title条的0属性 @『区块名称』不能为空。

*/

global $tester;
$tester->loadModel('block');

initData();

$block = new stdclass();
$block->id        = '2';
$block->account   = 'admin';
$block->dashboard = 'my';
$block->module    = 'project';
$block->code      = 'statistic';
$block->title     = '项目统计区块';
$block->width     = '1';

$emptyModuleBlock = new stdclass();
$emptyModuleBlock->id     = '4';
$emptyModuleBlock->module = '';

$emptyCodeBlock = new stdclass();
$emptyCodeBlock->id   = '4';
$emptyCodeBlock->code = '';

$emptyTitleBlock = new stdclass();
$emptyTitleBlock->id    = '4';
$emptyTitleBlock->title = '';

$blockTest = new blockModelTest();
$newBlockID = $blockTest->updateTest($block);

r($tester->block->getByID($newBlockID)) && p('account,dashboard,module,code,title,vision,width,height') && e('admin,my,project,statistic,项目统计区块,rnd,1,3'); // 测试获取正常的block的内容
r($blockTest->updateTest($emptyModuleBlock)) && p('module:0') && e('『所属模块』不能为空。'); // 测试所属模块为空
r($blockTest->updateTest($emptyCodeBlock)) && p('code:0') && e('『区块』不能为空。'); // 测试所属区块为空
r($blockTest->updateTest($emptyTitleBlock)) && p('title:0') && e('『区块名称』不能为空。'); // 测试区块名称为空
