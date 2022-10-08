#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getStatisticParams();
cid=1
pid=1

获取空区块的参数 >> {"count":{"name":"数量","default":20,"control":"input"}}
获取产品区块的参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"noclosed":"未关闭","closed":"已关闭","all":"全部","involved":"我参与"},"control":"select"}}
获取项目区块的参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"undone":"未完成","doing":"进行中","all":"全部","involved":"我参与的"},"control":"select"}}
获取执行区块的参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"undone":"未完成","doing":"进行中","all":"所有","involved":"我参与"},"control":"select"}}
获取测试区块的参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"noclosed":"未关闭","closed":"已关闭","all":"全部","involved":"我参与"},"control":"select"}}

*/

$block = new blockTest();

$data    = array();
$data[0] = $block->getStatisticParamsTest('');
$data[1] = $block->getStatisticParamsTest('product');
$data[2] = $block->getStatisticParamsTest('project');
$data[3] = $block->getStatisticParamsTest('execution');
$data[4] = $block->getStatisticParamsTest('qa');

r($data[0]) && p() && e('{"count":{"name":"数量","default":20,"control":"input"}}');  // 获取空区块的参数
r($data[1]) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"noclosed":"未关闭","closed":"已关闭","all":"全部","involved":"我参与"},"control":"select"}}');  // 获取产品区块的参数
r($data[2]) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"undone":"未完成","doing":"进行中","all":"全部","involved":"我参与的"},"control":"select"}}');   // 获取项目区块的参数
r($data[3]) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"undone":"未完成","doing":"进行中","all":"所有","involved":"我参与"},"control":"select"}}');     // 获取执行区块的参数
r($data[4]) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"noclosed":"未关闭","closed":"已关闭","all":"全部","involved":"我参与"},"control":"select"}}');  // 获取测试区块的参数