#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getProductStatisticParams();
cid=1
pid=1

获取产品统计区块的参数 >> count:{name:数量,default:20,control:input};type:{name:类型,noclosed=>未关闭,closed=>已关闭,all=>全部,involved=>我参与,control:select};

*/

$block = new blockTest();

r($block->getProductStatisticParamsTest()) && p() && e('count:{name:数量,default:20,control:input};type:{name:类型,noclosed=>未关闭,closed=>已关闭,all=>全部,involved=>我参与,control:select};'); // 获取产品统计区块的参数
