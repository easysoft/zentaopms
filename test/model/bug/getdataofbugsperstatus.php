#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerStatus();
cid=1
pid=1

获取状态为active的数据 >> 激活,165
获取状态为resolved的数据 >> 已解决,90
获取状态为closed的数据 >> 已关闭,60

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerStatusTest()) && p('active:name,value')   && e('激活,165');  // 获取状态为active的数据
r($bug->getDataOfBugsPerStatusTest()) && p('resolved:name,value') && e('已解决,90'); // 获取状态为resolved的数据
r($bug->getDataOfBugsPerStatusTest()) && p('closed:name,value')   && e('已关闭,60'); // 获取状态为closed的数据