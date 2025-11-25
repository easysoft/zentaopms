#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';
su('admin');

zenData('dept')->loadYaml('dept')->gen(30);

/**

title=测试 deptModel->manageChild();
timeout=0
cid=15979

- 三级部门下添加四级部门属性1 @32
- 三级部门下添加四级部门统计 @3
- 无父级部门下添加子级部门属性1 @38
- 无父级部门下添加子级部门统计 @3
- 无部门名称 @0

*/
$parentDeptID = '28';
$depts        = array('四级部门一', '四级部门二', '四级部门三');
$count        = array('0', '1');

$dept = new deptTest();
r($dept->manageChildTest($parentDeptID, $depts, $count[0]))           && p('1') && e('32'); //三级部门下添加四级部门
r($dept->manageChildTest($parentDeptID, $depts, $count[1]))           && p()    && e('3');  //三级部门下添加四级部门统计
r($dept->manageChildTest(0, $depts, $count[0]))                       && p('1') && e('38'); //无父级部门下添加子级部门
r($dept->manageChildTest(0, $depts, $count[1]))                       && p()    && e('3');  //无父级部门下添加子级部门统计
r($dept->manageChildTest($parentDeptID, $depts = array(), $count[0])) && p()    && e('0');  //无部门名称

