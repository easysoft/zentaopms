#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->initBlock();
cid=1
pid=1

获取敏捷项目的区块版本 >> 1;2
获取敏捷项目的区块数据 >> project;scrum
获取瀑布项目的区块版本 >> 1;2
获取瀑布项目的区块数据 >> project;waterfall
获取看板项目的区块版本 >> 1;2
获取看板项目的区块数据 >> project;kanban
获取产品的区块版本 >> 1;2
获取产品的区块数据 >> product;
获取执行的区块版本 >> 1;2
获取执行的区块数据 >> execution;
获取地盘的区块版本 >> 1;2
获取地盘的区块数据 >> my;
获取测试的区块版本 >> 1;2
获取测试的区块数据 >> qa;
获取空区块版本 >> 1;2
获取空区块数据 >> 0

*/

$block = new blockTest();

$dataList    = array();
$dataList[0] = $block->initBlockTest('project', 'scrum');
$dataList[1] = $block->initBlockTest('project', 'waterfall');
$dataList[2] = $block->initBlockTest('project', 'kanban');
$dataList[3] = $block->initBlockTest('product');
$dataList[4] = $block->initBlockTest('execution');
$dataList[5] = $block->initBlockTest('my');
$dataList[6] = $block->initBlockTest('qa');
$dataList[7] = $block->initBlockTest('');

r($dataList[0]) && p("blockInited;blockversion")        && e('1;2');               // 获取敏捷项目的区块版本
r($dataList[0]) && p("blockData:module;blockData:type") && e('project;scrum');     // 获取敏捷项目的区块数据
r($dataList[1]) && p("blockInited;blockversion")        && e('1;2');               // 获取瀑布项目的区块版本
r($dataList[1]) && p("blockData:module;blockData:type") && e('project;waterfall'); // 获取瀑布项目的区块数据
r($dataList[2]) && p("blockInited;blockversion")        && e('1;2');               // 获取看板项目的区块版本
r($dataList[2]) && p("blockData:module;blockData:type") && e('project;kanban');    // 获取看板项目的区块数据
r($dataList[3]) && p("blockInited;blockversion")        && e('1;2');               // 获取产品的区块版本
r($dataList[3]) && p("blockData:module;blockData:type") && e('product;');          // 获取产品的区块数据
r($dataList[4]) && p("blockInited;blockversion")        && e('1;2');               // 获取执行的区块版本
r($dataList[4]) && p("blockData:module;blockData:type") && e('execution;');        // 获取执行的区块数据
r($dataList[5]) && p("blockInited;blockversion")        && e('1;2');               // 获取地盘的区块版本
r($dataList[5]) && p("blockData:module;blockData:type") && e('my;');               // 获取地盘的区块数据
r($dataList[6]) && p("blockInited;blockversion")        && e('1;2');               // 获取测试的区块版本
r($dataList[6]) && p("blockData:module;blockData:type") && e('qa;');               // 获取测试的区块数据
r($dataList[7]) && p("blockInited;blockversion")        && e('1;2');               // 获取空区块版本
r($dataList[7]) && p("blockData")                       && e('0');                 // 获取空区块数据

