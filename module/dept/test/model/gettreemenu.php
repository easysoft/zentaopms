#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('dept')->gen(30);

/**

title=测试 deptModel->getTreeMenu();
timeout=0
cid=15977

- 全部部门树结构查询第0条的name属性 @产品部1
- 无子部门树结构查询第0条的name属性 @产品部1
- 有子部门树结构查询第0条的name属性 @开发部2
- 有子部门树结构查询第0条的name属性 @测试部3
- 有子部门树结构查询第0条的name属性 @运营部4

*/

$deptIDList = array(0, 1, 2, 3, 4);
$userFunc   = array('deptmodel', 'createMemberLink');

$dept = new deptModelTest();
r($dept->getTreeMenuTest($deptIDList[0], $userFunc)) && p('0:name') && e('产品部1'); //全部部门树结构查询
r($dept->getTreeMenuTest($deptIDList[1], $userFunc)) && p('0:name') && e('产品部1'); //无子部门树结构查询
r($dept->getTreeMenuTest($deptIDList[2], $userFunc)) && p('0:name') && e('开发部2'); //有子部门树结构查询
r($dept->getTreeMenuTest($deptIDList[3], $userFunc)) && p('0:name') && e('测试部3'); //有子部门树结构查询
r($dept->getTreeMenuTest($deptIDList[4], $userFunc)) && p('0:name') && e('运营部4'); //有子部门树结构查询
