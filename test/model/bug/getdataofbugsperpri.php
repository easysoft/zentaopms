#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerPri();
cid=1
pid=1

获取pri为1的数据 >> 优先级:1,75
获取pri为2的数据 >> 优先级:2,75
获取pri为3的数据 >> 优先级:3,75
获取pri为4的数据 >> 优先级:4,75

*/


$bug=new bugTest();
r($bug->getDataOfBugsPerPriTest()) && p('1:name,value') && e('优先级:1,75'); // 获取pri为1的数据
r($bug->getDataOfBugsPerPriTest()) && p('2:name,value') && e('优先级:2,75'); // 获取pri为2的数据
r($bug->getDataOfBugsPerPriTest()) && p('3:name,value') && e('优先级:3,75'); // 获取pri为3的数据
r($bug->getDataOfBugsPerPriTest()) && p('4:name,value') && e('优先级:4,75'); // 获取pri为4的数据
