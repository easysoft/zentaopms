#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

zdTable('dept')->config('dept')->gen(50)->fixPath();
/**

title=测试 deptModel->getChildDepts();
timeout=0
cid=1

- 测试获取ID为6的部门名称第6条的name属性 @开发部26
- 测试获取父ID为2且ID为8的部门名称第8条的name属性 @一级部门8

*/

global $tester;
$tester->loadModel('dept');

r($tester->dept->getChildDepts(0)) && p('6:name') && e('开发部26');  // 测试获取ID为6的部门名称
r($tester->dept->getChildDepts(2)) && p('8:name') && e('一级部门8'); // 测试获取父ID为2且ID为8的部门名称
