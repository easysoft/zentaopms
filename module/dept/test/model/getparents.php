#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';
su('admin');

zenData('dept')->gen(30);

/**

title=测试 deptModel->getParents();
timeout=0
cid=15975

- 父级部门查询父级
 - 第0条的id属性 @2
 - 第0条的name属性 @开发部2
 - 第0条的parent属性 @0
- 子级部门查询父级
 - 第0条的id属性 @5
 - 第0条的name属性 @开发部15
 - 第0条的parent属性 @0
- 父级部门查询父级统计 @1
- 子级部门查询父级统计 @1
- 不存在的子级部门查询父级统计 @0

*/

$deptIDList = array('2', '5', '50');
$count      = array('0', '1');

$dept = new deptTest();
r($dept->getParentsTest($deptIDList[0], $count[0])) && p('0:id,name,parent') && e('2,开发部2,0');   //父级部门查询父级
r($dept->getParentsTest($deptIDList[1], $count[0])) && p('0:id,name,parent') && e('5,开发部15,0');  //子级部门查询父级
r($dept->getParentsTest($deptIDList[0], $count[1])) && p()                   && e('1');             //父级部门查询父级统计
r($dept->getParentsTest($deptIDList[1], $count[1])) && p()                   && e('1');             //子级部门查询父级统计
r($dept->getParentsTest($deptIDList[2], $count[1])) && p()                   && e('0');             //不存在的子级部门查询父级统计
