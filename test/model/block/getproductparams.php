#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getProductParams();
cid=1
pid=1

获取产品区块的参数 >> type:{name:类型,noclosed=>未关闭,closed=>已关闭,all=>全部,involved=>我参与,control:select};count:{name:数量,default:20,control:input};

*/

$block = new blockTest();

r($block->getProductParamsTest()) && p() && e('type:{name:类型,noclosed=>未关闭,closed=>已关闭,all=>全部,involved=>我参与,control:select};count:{name:数量,default:20,control:input};'); // 获取产品区块的参数
