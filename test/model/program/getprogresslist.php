#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModee::getProgressList();
cid=1
pid=1

获取项目和项目集的个数 >> 100
获取id=1的项目的进度 >> 0
获取id=11的项目集的进度 >> 45

*/

$getItemsets = new Program('admin');

r($getItemsets->getCount3())       && p()     && e('100'); // 获取项目和项目集的个数
r($getItemsets->getProgressList()) && p('1')  && e('0');   // 获取id=1的项目的进度
r($getItemsets->getProgressList()) && p('11') && e('45');   // 获取id=11的项目集的进度