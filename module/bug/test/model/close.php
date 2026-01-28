#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->loadYaml('bug_close')->gen(55);
zenData('user')->gen(1);
zenData('product')->gen(55);

zenData('project')->loadYaml('project_close')->gen(55);
zenData('kanbancell')->loadYaml('kanbancell')->gen(27);
zenData('kanbancolumn')->loadYaml('kanbancolumn')->gen(27);
zenData('kanbanlane')->loadYaml('kanbanlane')->gen(3);
zenData('kanbanregion')->loadYaml('kanbanregion')->gen(1);

su('admin');

/**

title=bugModel->close();
cid=15348

- 测试关闭状态为active的bug1
 - 属性title @第1个bug
 - 属性status @closed
 - 属性assignedTo @closed
 - 属性confirmed @1
- 测试关闭状态为resolved的bug2
 - 属性title @第2个bug
 - 属性status @closed
 - 属性assignedTo @closed
 - 属性confirmed @1
- 测试关闭状态为closed的bug3
 - 属性title @第3个bug
 - 属性status @closed
 - 属性assignedTo @closed
 - 属性confirmed @1
- 测试关闭状态为active的看板执行bug7
 - 属性title @第7个bug
 - 属性status @closed
 - 属性assignedTo @closed
 - 属性confirmed @1
- 测试关闭状态为resolved的看板执行bug8
 - 属性title @第8个bug
 - 属性status @closed
 - 属性assignedTo @closed
 - 属性confirmed @1
- 测试关闭状态为closed的看板执行bug9
 - 属性title @第9个bug
 - 属性status @closed
 - 属性assignedTo @closed
 - 属性confirmed @1

*/

$bugIDList = array(1, 2, 3, 7, 8, 9);
$output['fromColID']  = 23;
$output['toColID']    = 22;
$output['fromLaneID'] = 3;
$output['toLaneID']   = 3;
$output['regionID']   = 1;

$bug=new bugModelTest();
r($bug->closeObject($bugIDList[0])) && p('title,status,assignedTo,confirmed') && e('第1个bug,closed,closed,1'); // 测试关闭状态为active的bug1
r($bug->closeObject($bugIDList[1])) && p('title,status,assignedTo,confirmed') && e('第2个bug,closed,closed,1'); // 测试关闭状态为resolved的bug2
r($bug->closeObject($bugIDList[2])) && p('title,status,assignedTo,confirmed') && e('第3个bug,closed,closed,1'); // 测试关闭状态为closed的bug3
r($bug->closeObject($bugIDList[3])) && p('title,status,assignedTo,confirmed') && e('第7个bug,closed,closed,1'); // 测试关闭状态为active的看板执行bug7
r($bug->closeObject($bugIDList[4])) && p('title,status,assignedTo,confirmed') && e('第8个bug,closed,closed,1'); // 测试关闭状态为resolved的看板执行bug8
r($bug->closeObject($bugIDList[5])) && p('title,status,assignedTo,confirmed') && e('第9个bug,closed,closed,1'); // 测试关闭状态为closed的看板执行bug9
