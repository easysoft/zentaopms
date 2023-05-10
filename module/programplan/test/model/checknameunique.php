#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->checkNameUnique();
cid=1
pid=1

*/

$names = array();
$names[0] = array('name1', 'name2', 'name1', 'name3', 'name5');
$names[1] = array('name6', 'name7');
$names[2] = array('name2', 'name2');

$programplan = new programplanTest();
r($programplan->checkNameUniqueTest($names[0])) && p() && e('0'); // 检查 'name1', 'name2', 'name1', 'name3', 'name5' 数组的值是否唯一
r($programplan->checkNameUniqueTest($names[1])) && p() && e('1'); // 检查 'name6', 'name7' 数组的值是否唯一
r($programplan->checkNameUniqueTest($names[2])) && p() && e('0'); // 检查 'name2', 'name2' 数组的值是否唯一
