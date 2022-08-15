#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getWelcomeBlockData();
cid=1
pid=1

获取欢迎区块的数据 >> {"tasks":"0","doneTasks":"0","bugs":85,"stories":0,"executions":739,"products":80,"delayTask":0,"delayBug":74,"delayProject":0}

*/

$block = new blockTest();

r($block->getWelcomeBlockDataTest()) && p() && e('{"tasks":"0","doneTasks":"0","bugs":85,"stories":0,"executions":739,"products":80,"delayTask":0,"delayBug":74,"delayProject":0}'); // 获取欢迎区块的数据
