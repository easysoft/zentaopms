#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel->createImage().
timeout=0
cid=1

- zanodeID为0的时候，执行节点不存在，返回false。 @0
- 新建镜像成功并且在宿主机注册成功的时候，返回镜像id，为整型。 @1
- 新建镜像成功并且在宿主机注册成功的时候，宿主机状态为creating_img。 @1
- 新建镜像并且宿主机注册成功的时候，数据库会新增一条记录。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(2);
su('admin');

$zanodeIDList = array(0, 1);
$zanode = new zanodeTest();

$data = new stdclass();
$data->name = 'test node';

$num1 = $tester->dao->select('count(1) as num')->from(TABLE_IMAGE)->fetch('num');

r($zanode->createImage($zanodeIDList[0], $data)) && p('') && e(0);  //zanodeID为0的时候，执行节点不存在，返回false。
$result = $zanode->createImage($zanodeIDList[1], $data);
r(is_int($result)) && p('') && e(1);  //新建镜像成功并且在宿主机注册成功的时候，返回镜像id，为整型。
$zahost = $tester->dao->select('id, status')->from(TABLE_HOST)->where('id')->eq(1)->fetchPairs();
r($zahost[1] === 'creating_img') && p('') && e(1);  //新建镜像成功并且在宿主机注册成功的时候，宿主机状态为creating_img。

$num2 = $tester->dao->select('count(1) as num')->from(TABLE_IMAGE)->fetch('num');
r($num2 - $num1) && p('') && e(1);  //新建镜像并且宿主机注册成功的时候，数据库会新增一条记录。