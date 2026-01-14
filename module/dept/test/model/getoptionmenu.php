#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('dept')->gen(30);

/**

title=测试 deptModel->getOptionMenu();
cid=15974
pid=1

父级部门查询     >> /产品部1
多级部门查询     >> /开发部2
全部部门查询     >> /开发部15
父级部门查询统计 >> 2
多级部门查询统计 >> 2
全部部门查询统计 >> 31

*/

$deptIDList = array('0', '1', '2');
$count      = array('0', '1');

$dept = new deptModelTest();
r($dept->getOptionMenuTest($deptIDList[1], $count[0])) && p('1') && e('/产品部1');  //父级部门查询
r($dept->getOptionMenuTest($deptIDList[2], $count[0])) && p('2') && e('/开发部2');  //多级部门查询
r($dept->getOptionMenuTest($deptIDList[0], $count[0])) && p('5') && e('/开发部15'); //全部部门查询
r($dept->getOptionMenuTest($deptIDList[1], $count[1])) && p()    && e('2');         //父级部门查询统计
r($dept->getOptionMenuTest($deptIDList[2], $count[1])) && p()    && e('2');         //多级部门查询统计
r($dept->getOptionMenuTest($deptIDList[0], $count[1])) && p()    && e('31');        //全部部门查询统计
