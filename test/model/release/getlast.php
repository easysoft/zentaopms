#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->getLast();
cid=1
pid=1

产品ID正常查询 >> 9,产品1发布9
产品ID为空查询 >> 0
产品ID不存在查询 >> 0

*/

$productID = array('1', '10000');

$release = new releaseTest();

r($release->getLastTest($productID[0])) && p('id,name')   && e('9,产品1发布9'); //产品ID正常查询
r($release->getLastTest(''))            && p('')          && e('0');            //产品ID为空查询
r($release->getLastTest($productID[1])) && p('')          && e('0');            //产品ID不存在查询
