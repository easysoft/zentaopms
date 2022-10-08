#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getBlockList();
cid=1
pid=1

 >> 测试统计;指派给我的Bug;指派给我的用例;待测版本列表
 >> 项目计划;最新计划

*/

$block = new blockTest();

r($block->getBlockListTest('qa'))                   && p('107:title;108:title;109:title;110:title') && e('测试统计;指派给我的Bug;指派给我的用例;待测版本列表');
r($block->getBlockListTest('project', 'waterfall')) && p('105:title;106:title')                     && e('项目计划;最新计划');