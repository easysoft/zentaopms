#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getInvolvedPrograms();
cid=1
pid=1

查看用户admin可以看到的项目和执行id列表 >> 131
查看用户test2可以看到的项目和执行id列表 >> 1

*/

$listOfId = new Program('admin');

$t_sulist = array('admin', 'test2');

/* GetInvolvedPrograms($account). */
r($listOfId->getInvolvedPrograms($t_sulist[0])) && p('131')   && e('131');   // 查看用户admin可以看到的项目和执行id列表
r($listOfId->getInvolvedPrograms($t_sulist[1])) && p('1') && e('1'); // 查看用户test2可以看到的项目和执行id列表