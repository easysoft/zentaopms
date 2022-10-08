#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/search.class.php';
su('admin');

/**

title=测试 searchModel->saveIndex();
cid=1
pid=1

创建ID为10的bug的索引 >> 1
创建ID为10的任务的索引 >> 1

*/

$search = new searchTest();

r($search->saveIndexTest('bug', 10))   && p() && e('1'); //创建ID为10的bug的索引
r($search->saveIndexTest('task', 10))  && p() && e('1'); //创建ID为10的任务的索引