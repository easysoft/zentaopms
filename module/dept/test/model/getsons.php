#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('dept')->loadYaml('dept')->gen(13);

/**

title=测试 deptModel->getSons();
timeout=0
cid=15976

- 有子部门查询
 - 第0条的name属性 @一级部门8
 - 第0条的parent属性 @2
 - 第0条的path属性 @,8,
- 无子部门查询 @0
- 子部门数量统计 @2
- 不存在的子部门数量统计 @0
- 不存在的子部门数量统计 @0

*/

$deptIDList = array('2', '5', '20');
$count      = array('0', '1');

$dept = new deptModelTest();
r($dept->getSonsTest($deptIDList[0], $count[0])) && p('0:name|parent|path', '|') && e('一级部门8|2|,8,');   //有子部门查询
r($dept->getSonsTest($deptIDList[1], $count[0])) && p()                          && e('0');                 //无子部门查询
r($dept->getSonsTest($deptIDList[0], $count[1])) && p()                          && e('2');                 //子部门数量统计
r($dept->getSonsTest($deptIDList[1], $count[1])) && p()                          && e('0');                 //不存在的子部门数量统计
r($dept->getSonsTest($deptIDList[2], $count[1])) && p()                          && e('0');                 //不存在的子部门数量统计
