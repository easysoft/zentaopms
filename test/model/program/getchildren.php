#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel:: getChildren();
cid=1
pid=1

通过id查找id=1的子项目集个数 >> 9
通过id查找id=22的子项目集个数 >> 7
通过id查找id=221的子项目集个数 >> Not Found

*/

$SubprojectSet = new Program('admin');

$t_findnu = array('1', '22', '221');

r($SubprojectSet->getChildren($t_findnu[0])) && p()          && e('9');           // 通过id查找id=1的子项目集个数
r($SubprojectSet->getChildren($t_findnu[1])) && p()          && e('7');           // 通过id查找id=22的子项目集个数
r($SubprojectSet->getChildren($t_findnu[2])) && p('message') && e('Not Found');   // 通过id查找id=221的子项目集个数