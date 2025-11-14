#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->loadYaml('casebug')->gen(10);
zenData('case')->gen(10);
zenData('task')->gen(10);

/**

title=测试 bugTao::getNameFromTable;
timeout=0
cid=15418


*/

$bugIdList  = array('1,2,3', '1,4', '2,7', '2,6,9', '1000001');

$idList = array(1, 2, 3);
$table  = array(TABLE_BUG, TABLE_CASE, TABLE_TASK);
$field  = array('title', 'name');

global $tester;
$tester->loadModel('bug');

r($tester->bug->getNameFromTable($idList[0], $table[0], $field[0])) && p()  && e('测试单转Bug1');    // 获取 ID 等于 1 的 bug 的 title 值
r($tester->bug->getNameFromTable($idList[1], $table[0], $field[0])) && p()  && e('SonarQube_Bug2');  // 获取 ID 等于 2 的 bug 的 title 值
r($tester->bug->getNameFromTable($idList[2], $table[0], $field[0])) && p()  && e('测试单转Bug3');    // 获取 ID 等于 3 的 bug 的 title 值
r($tester->bug->getNameFromTable($idList[0], $table[1], $field[0])) && p()  && e('这个是测试用例1'); // 获取 ID 等于 1 的 case 的 title 值
r($tester->bug->getNameFromTable($idList[1], $table[1], $field[0])) && p()  && e('这个是测试用例2'); // 获取 ID 等于 2 的 case 的 title 值
r($tester->bug->getNameFromTable($idList[2], $table[1], $field[0])) && p()  && e('这个是测试用例3'); // 获取 ID 等于 3 的 case 的 title 值
r($tester->bug->getNameFromTable($idList[0], $table[2], $field[1])) && p()  && e('开发任务11');      // 获取 ID 等于 1 的 task 的 name 值
r($tester->bug->getNameFromTable($idList[1], $table[2], $field[1])) && p()  && e('开发任务12');      // 获取 ID 等于 2 的 task 的 name 值
r($tester->bug->getNameFromTable($idList[2], $table[2], $field[1])) && p()  && e('开发任务13');      // 获取 ID 等于 3 的 task 的 name 值
