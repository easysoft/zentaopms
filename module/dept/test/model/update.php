#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

zdTable('dept')->gen(30);

/**

title=测试 deptModel->update();
cid=1
pid=1

修改父级       >> 修改后部门|0|,16,|dev1
修改子级       >> 子级部门修改|1|,1,18,|dev2
不输入上级部门 >> 17,一级部门7
部门名称为空   >> 19,二级部门9
无负责人       >> 无负责人部门|1|,1,20,|N/A

*/

$parentDept = array('id' => 16, 'parent' => '0', 'name' => '修改后部门', 'manager' => 'dev1');
$childDept  = array('id' => 18, 'parent' => '1', 'name' => '子级部门修改', 'manager' => 'dev2');
$noParent   = array('id' => 17, 'name' => '无父级部门', 'manager' => 'test1');
$noName     = array('id' => 19, 'parent' => '0', 'manager' => 'test2');
$noManager  = array('id' => 20, 'parent' => '1', 'name' => '无负责人部门');

$dept = new deptTest();
r($dept->updateTest((object)$parentDept)) && p('16:name|parent|path|manager', '|')  && e('修改后部门|0|,16,|dev1');     //修改父级
r($dept->updateTest((object)$childDept))  && p('18:name|parent|path|manager', '|')  && e('子级部门修改|1|,1,18,|dev2'); //修改子级
r($dept->updateTest((object)$noParent))   && p('17:id,name')                        && e('17,无父级部门');               //不输入上级部门
r($dept->updateTest((object)$noName))     && p('19:id,name')                        && e('19,二级部门9');               //部门名称为空
r($dept->updateTest((object)$noManager))  && p('20:name|parent|path', '|')  && e('无负责人部门|1|,1,20,');  //无负责人
