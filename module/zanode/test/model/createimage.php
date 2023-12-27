#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel->createImage().
timeout=0
cid=1

- zanodeID为0的时候，执行节点不存在，返回false。 @0
- zanodeID为1,并且数据数据正常更新的时候，由于目前没有更好的执行节点解决方案，所以返回false。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(1);
su('admin');

$zanodeIDList = array(0, 1);
$zanode = new zanodeTest();

$data = new stdclass();
$data->name = 'test node';

r($zanode->createImage($zanodeIDList[0], $data)) && p('') && e(0);  //zanodeID为0的时候，执行节点不存在，返回false。
r($zanode->createImage($zanodeIDList[1], $data)) && p('') && e(0);  //zanodeID为1,并且数据数据正常更新的时候，由于目前没有更好的执行节点解决方案，所以返回false。