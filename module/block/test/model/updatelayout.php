#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $block = zenData('block');
    $block->id->range('1-10');
    $block->account->range('admin');
    $block->dashboard->range('my{4},project{3},product{3}');
    $block->module->range('my{4},project{3},product{3}');
    $block->title->range('测试区块1{3},测试区块2{3},测试区块3{4}');
    $block->block->range('welcome{2},guide{3},list{5}');
    $block->code->range('welcome{2},guide{3},list{5}');
    $block->width->range('1{3},2{4},3{3}');
    $block->height->range('3{5},4{3},5{2}');
    $block->left->range('0{4},1{3},2{3}');
    $block->top->range('0{3},3{3},6{2},9{2}');
    $block->params->range('{"count":10}{3},{"type":"list"}{3},{}{4}');
    $block->hidden->range('0{8},1{2}');
    $block->vision->range('rnd{8},lite{2}');

    $block->gen(10);
}

/**

title=测试 blockModel::updateLayout();
timeout=0
cid=15236

- 执行block模块的updateLayout方法，参数是array  @1
- 执行block模块的updateLayout方法，参数是array  @1
- 执行block模块的updateLayout方法，参数是array  @1
- 执行block模块的updateLayout方法，参数是array  @1
- 执行block模块的updateLayout方法，参数是array  @1

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->updateLayout(array(1 => array('left' => 1, 'top' => 0)))) && p() && e('1');
r($tester->block->updateLayout(array(1 => array('left' => 2, 'top' => 3), 2 => array('left' => 0, 'top' => 6)))) && p() && e('1');
r($tester->block->updateLayout(array())) && p() && e('1');
r($tester->block->updateLayout(array(999 => array('left' => 1, 'top' => 0)))) && p() && e('1');
r($tester->block->updateLayout(array(1 => array('left' => 1, 'top' => 0, 'invalid' => 'test')))) && p() && e('1');
