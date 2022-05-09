#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getListParams();
cid=1
pid=1

测试通过模块获取区块的参数 >> 数量
测试通过模块获取区块的参数 >> 数量
测试通过模块获取区块的参数 >> 数量;类型;排序

*/

$block = new blockTest();
$data[0] = $block->getListParamsTest('my');
$data[1] = $block->getListParamsTest('qa');
$data[2] = $block->getListParamsTest('project');

r($data[0]) && p('count:name')                        && e('数量');           //测试通过模块获取区块的参数
r($data[1]) && p('count:name')                        && e('数量');           //测试通过模块获取区块的参数
r($data[2]) && p('count:name;type:name;orderBy:name') && e('数量;类型;排序'); //测试通过模块获取区块的参数