#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerActivatedCount();
cid=1
pid=1

获取bug激活次数数据 >> 激活次数:0,315

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerActivatedCountTest()) && p('0:name,value') && e('激活次数:0,315');   // 获取bug激活次数数据