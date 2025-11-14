#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';
su('admin');

zenData('dept')->gen(30);

/**

title=测试 deptModel->getDataStructure();
timeout=0
cid=15970

- 全部查询
 - 第29条的id属性 @30
 - 第29条的name属性 @三级部门10
- 全部查询
 - 第28条的id属性 @29
 - 第28条的name属性 @三级部门9
- 全部查询
 - 第27条的id属性 @28
 - 第27条的name属性 @三级部门8
- 全部查询
 - 第26条的id属性 @27
 - 第26条的name属性 @二级部门7
- 全部查询统计 @30

*/

$count = array('0', '1');

$dept = new deptTest();
r($dept->getDataStructureTest($count[0])) && p('29:id,name')  && e('30,三级部门10');  //全部查询
r($dept->getDataStructureTest($count[0])) && p('28:id,name')  && e('29,三级部门9');   //全部查询
r($dept->getDataStructureTest($count[0])) && p('27:id,name')  && e('28,三级部门8');   //全部查询
r($dept->getDataStructureTest($count[0])) && p('26:id,name')  && e('27,二级部门7');   //全部查询
r($dept->getDataStructureTest($count[1])) && p()              && e('30');             //全部查询统计
