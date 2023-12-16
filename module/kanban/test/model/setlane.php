#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanlane')->gen(5);

/**

title=测试设置泳道1 >> 修改泳道1,
timeout=0
cid=333

- 测试设置泳道1
 - 属性name @修改泳道1
 - 属性color @333
- 测试设置泳道2
 - 属性name @修改泳道2
 - 属性color @2b529c
- 测试设置泳道3
 - 属性name @修改泳道3
 - 属性color @333
- 测试设置泳道4
 - 属性name @修改泳道4
 - 属性color @2b529c
- 测试设置泳道5
 - 属性name @修改泳道5
 - 属性color @333
- 测试设置不填写名字第name条的0属性 @『泳道名称』不能为空。

*/

$idList    = array('1' ,'2', '3', '4', '5');
$nameList  = array('修改泳道1', '修改泳道2', '修改泳道3', '修改泳道4', '修改泳道5', '');
$colorList = array('333', '2b529c');

$kanban = new kanbanTest();

r($kanban->setLaneTest($idList[0], $nameList[0], $colorList[0])) && p('name,color') && e('修改泳道1,333');          // 测试设置泳道1
r($kanban->setLaneTest($idList[1], $nameList[1], $colorList[1])) && p('name,color') && e('修改泳道2,2b529c');       // 测试设置泳道2
r($kanban->setLaneTest($idList[2], $nameList[2], $colorList[0])) && p('name,color') && e('修改泳道3,333');          // 测试设置泳道3
r($kanban->setLaneTest($idList[3], $nameList[3], $colorList[1])) && p('name,color') && e('修改泳道4,2b529c');       // 测试设置泳道4
r($kanban->setLaneTest($idList[4], $nameList[4], $colorList[0])) && p('name,color') && e('修改泳道5,333');          // 测试设置泳道5
r($kanban->setLaneTest($idList[4], $nameList[5], $colorList[0])) && p('name:0')     && e('『泳道名称』不能为空。'); // 测试设置不填写名字