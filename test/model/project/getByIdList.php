#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getByIdList();
cid=1
pid=1

获取projectIdList对应的项目名称 >> 项目1;项目2;项目3

*/

$t = new Project('admin');

$projectIdList = array(11,12,13);

r(($t->getByIdList($projectIdList, 3))) && p('11:name;12:name;13:name') && e('项目1;项目2;项目3'); //获取projectIdList对应的项目名称