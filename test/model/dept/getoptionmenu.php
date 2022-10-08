#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getOptionMenu();
cid=1
pid=1

父级部门查询 >> /产品部
多级部门查询 >> 开发部
全部部门查询 >> /开发部/开发部1
父级部门查询统计 >> 2
多级部门查询统计 >> 4
全部部门查询统计 >> 101

*/

$deptIDList = array('0', '1', '2');
$count      = array('0', '1');

$dept = new deptTest();
r($dept->getOptionMenuTest($deptIDList[1], $count[0])) && p('1') && e('/产品部');         //父级部门查询
r($dept->getOptionMenuTest($deptIDList[2], $count[0])) && p('2') && e('开发部');          //多级部门查询
r($dept->getOptionMenuTest($deptIDList[0], $count[0])) && p('5') && e('/开发部/开发部1'); //全部部门查询
r($dept->getOptionMenuTest($deptIDList[1], $count[1])) && p()    && e('2');               //父级部门查询统计
r($dept->getOptionMenuTest($deptIDList[2], $count[1])) && p()    && e('4');               //多级部门查询统计
r($dept->getOptionMenuTest($deptIDList[0], $count[1])) && p()    && e('101');             //全部部门查询统计