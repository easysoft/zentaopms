#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

$programplan = zdTable('project');
$programplan->id->range('10-15');
$programplan->name->range('1-5')->prefix('name');
$programplan->deleted->range('0-1');
$programplan->gen(5);

/**

title=测试 programplanModel->checkNameUnique();
cid=1
pid=1

检查'name1', 'name2', 'name1', 'name3', 'name5' 数组的值是否唯一 >> 0
检查'name6', 'name7' 数组的值是否唯一 >> 1
检查'name2', 'name2' 数组的值是否唯一 >> 0

*/

$names = array();
$names[] = array('name1', 'name2', 'name1', 'name3', 'name5');
$names[] = array('name6', 'name7');
$names[] = array('name2', 'name2');

$programplan = new programplanTest();

r($programplan->checkNameUniqueTest($names[0])) && p() && e('0'); // 检查'name1', 'name2', 'name1', 'name3', 'name5' 数组的值是否唯一
r($programplan->checkNameUniqueTest($names[1])) && p() && e('1'); // 检查'name6', 'name7' 数组的值是否唯一
r($programplan->checkNameUniqueTest($names[2])) && p() && e('0'); // 检查'name2', 'name2' 数组的值是否唯一
