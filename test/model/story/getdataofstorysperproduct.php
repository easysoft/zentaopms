#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerProduct();
cid=1
pid=1

按照产品分组，获取分组后的需求数量 >> 100
按照产品分组，获取各个产品下的需求数量，查看产品79下的数据 >> 已关闭的多分支产品79,4

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerProduct();

r(count($data)) && p()                && e('100');                    // 按照产品分组，获取分组后的需求数量
r($data)        && p('79:name,value') && e('已关闭的多分支产品79,4'); // 按照产品分组，获取各个产品下的需求数量，查看产品79下的数据