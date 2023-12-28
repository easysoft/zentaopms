#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->getImagePairs();
timeout=0
cid=1

- 测试获取主机 1 的镜像列表数量 @2
- 查询镜像的键值对
 - 属性1 @defaultSnap1
 - 属性2 @defaultSnap2
 - 属性3 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$image = zdTable('image');
$image->config('image');
$image->status->range('completed');
$image->gen(2);

$hostID = 1;

$zahost = new zahostTest();
r(count($zahost->getImagePairs($hostID))) && p('') && e('2');                           //测试获取主机 1 的镜像列表数量
r($zahost->getImagePairs($hostID)) && p('1,2,3') && e('defaultSnap1,defaultSnap2,~~');  //查询镜像的键值对
