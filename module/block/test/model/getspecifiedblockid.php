#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $block = zdTable('block');
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

title=测试 block 模块 model下的 getMyDashboard 方法
timeout=0
cid=39

- 测试 能找到区块 返回值 是否正确 @2

- 测试 找不到区块 返回值 是否为 false @0

- 测试 不传参数 返回值 是否为 false @0

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getSpecifiedBlockID('my', 'welcome', 'welcome'))  && p('') && e('2'); // 测试 能找到区块 返回值 是否正确
r($tester->block->getSpecifiedBlockID('qa', 'bug', 'list'))  && p('') && e('0');        // 测试 找不到区块 返回值 是否为 false
r($tester->block->getSpecifiedBlockID('', '', ''))  && p('') && e('0');                 // 测试 不传参数 返回值 是否为 false
