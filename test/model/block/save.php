#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->save();
cid=1
pid=1



*/

$blockID = array('95', '99', '113');
$module  = 'product';

$changeTitle = new stdclass();
$changeTitle->modules    = 'welcome';
$changeTitle->title      = '欢迎test';
$changeTitle->grid       = 8;
$changeTitle->actionLink = '/block-set-95-welcome-.html';

$changeModules = new stdclass();
$changeModules->modules    = 'dynamic';
$changeModules->title      = '我的贡献';
$changeModules->grid       = 8;
$changeModules->actionLink = '/block-set-95-welcome-.html';

$changeBlock = new stdclass();
$changeBlock->modules     = 'project';
$changeBlock->title       = '项目统计';
$changeBlock->moduleBlock = 'projectteam';
$changeBlock->grid        = 8;
$changeBlock->params      = array('type' => 'all', 'orderBy' => 'id_asc', 'count' => 20);
$changeBlock->actionLink  = '/block-set-95-welcome-.html';

$changeParams = new stdclass();
$changeParams->modules     = 'product';
$changeParams->title       = '未关闭的产品';
$changeParams->moduleBlock = 'statistic';
$changeParams->grid        = 8;
$changeParams->params      = array('type' => 'noclosed', 'count' => 30);
$changeParams->actionLink  = '/block-set-95-welcome-.html';

$block = new blockTest();

r($block->saveTest($changeTitle,   $blockID[0], $changeTitle->modules))                                       && p('title,module,grid,block') && e('欢迎test,my,8,welcome');            // 测试修改block 名称
r($block->saveTest($changeModules, $blockID[0], $changModules->modules))                                      && p('title,module,grid,block') && e('我的贡献,my,8,dynamic');            // 测试修改block 模块
r($block->saveTest($changeBlock,   $blockID[1], $changeBlock->modules,  $changeBlock->moduleBlock))           && p('title,module,grid,block') && e('项目统计,my,8,projectteam');        // 测试修改block 区块
r($block->saveTest($changeParams,  $blockID[2], $changeParams->modules, $changeParams->moduleBlock, $module)) && p('title,module,grid,block') && e('未关闭的产品,product,8,statistic'); // 测试修改block 变量
system('./ztest init');
