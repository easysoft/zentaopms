#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zahostemodel->getImageByNameAndHost().
timeout=0
cid=1

- 测试获取 host id 1 name defaultSnap1 的镜像
 - 属性id @1
 - 属性name @defaultSnap1
 - 属性from @snapshot
 - 属性status @creating
 - 属性createdBy @system
- 测试获取 host id 1 name defaultSnap2 的镜像
 - 属性id @2
 - 属性name @defaultSnap2
 - 属性from @snapshot
 - 属性status @creating
 - 属性createdBy @admin
- 测试获取 host id 2 name defaultSnap3 的镜像
 - 属性id @3
 - 属性name @defaultSnap3
 - 属性from @snapshot
 - 属性status @creating
 - 属性createdBy @system
- 测试获取 host id 2 name defaultSnap4 的镜像
 - 属性id @4
 - 属性name @defaultSnap4
 - 属性from @zentao
 - 属性status @creating
 - 属性createdBy @admin
- 测试获取 host id 3 name defaultSnap5 的镜像
 - 属性id @5
 - 属性name @defaultSnap5
 - 属性from @snapshot
 - 属性status @wait
 - 属性createdBy @system
- 测试获取 host id 5 name defaultSnap6 的空的镜像 @0
- 测试获取 空的 host id 0 的镜像 @0
- 测试获取 不存在的 node id 1000 的镜像 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';

zdTable('user')->gen(10);
zdTable('image')->config('image')->gen(5);

su('admin');

$zahost = new zahostTest();

$hostIdList = array(1, 2, 3, 0, 1000);
$nameList   = array('defaultSnap1', 'defaultSnap2', 'defaultSnap3', 'defaultSnap4', 'defaultSnap5', 'defaultSnap6', 'defaultSnap7');

r($zahost->getImageByNameAndHostID($nameList[0], $hostIdList[0])) && p('id,name,from,status,createdBy') && e('1,defaultSnap1,snapshot,creating,system'); // 测试获取 host id 1 name defaultSnap1 的镜像
r($zahost->getImageByNameAndHostID($nameList[1], $hostIdList[0])) && p('id,name,from,status,createdBy') && e('2,defaultSnap2,snapshot,creating,admin');  // 测试获取 host id 1 name defaultSnap2 的镜像
r($zahost->getImageByNameAndHostID($nameList[2], $hostIdList[1])) && p('id,name,from,status,createdBy') && e('3,defaultSnap3,snapshot,creating,system'); // 测试获取 host id 2 name defaultSnap3 的镜像
r($zahost->getImageByNameAndHostID($nameList[3], $hostIdList[1])) && p('id,name,from,status,createdBy') && e('4,defaultSnap4,zentao,creating,admin');    // 测试获取 host id 2 name defaultSnap4 的镜像
r($zahost->getImageByNameAndHostID($nameList[4], $hostIdList[2])) && p('id,name,from,status,createdBy') && e('5,defaultSnap5,snapshot,wait,system');     // 测试获取 host id 3 name defaultSnap5 的镜像
r($zahost->getImageByNameAndHostID($nameList[5], $hostIdList[2])) && p() && e('0'); // 测试获取 host id 5 name defaultSnap6 的空的镜像
r($zahost->getImageByNameAndHostID($nameList[6], $hostIdList[3])) && p() && e('0'); // 测试获取 空的 host id 0 的镜像
r($zahost->getImageByNameAndHostID($nameList[4], $hostIdList[4])) && p() && e('0'); // 测试获取 不存在的 node id 1000 的镜像