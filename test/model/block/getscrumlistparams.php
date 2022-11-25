#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getScrumListParams();
cid=1
pid=1

测试name和select >> 类型,select
测试options >> 未完成
测试options >> 进行中
测试options >> 全部
测试options >> 我参与

*/

$block = new blockTest();

r($block->getScrumListParamsTest()) && p('name,control')     && e('类型,select');  //测试name和select
r($block->getScrumListParamsTest()) && p('options:undone')   && e('未完成');  //测试options
r($block->getScrumListParamsTest()) && p('options:doing')    && e('进行中');  //测试options
r($block->getScrumListParamsTest()) && p('options:all')      && e('全部');  //测试options
r($block->getScrumListParamsTest()) && p('options:involved') && e('我参与');  //测试options