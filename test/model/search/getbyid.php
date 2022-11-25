#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/search.class.php';
su('admin');

/**

title=测试 searchModel->getByID();
cid=1
pid=1

查询ID为1的模块名及条件名 >> task,任务查询测试条件
查询ID为2的模块名及条件名 >> executionStory,需求查找条件
查询ID为3的模块名及条件名 >> user,用户测试条件
查询ID为4的模块名及条件名 >> projectBuild,项目版本搜索
查询ID为5的模块名及条件名 >> executionBuild,执行版本搜索
查询ID为6的模块名及条件名 >> design,设计搜索

*/

$search = new searchTest();

$queryIDList = array('1', '2', '3', '4', '5', '6');

r($search->getByIDTest($queryIDList[0])) && p('module,title') && e('task,任务查询测试条件');       //查询ID为1的模块名及条件名
r($search->getByIDTest($queryIDList[1])) && p('module,title') && e('executionStory,需求查找条件'); //查询ID为2的模块名及条件名
r($search->getByIDTest($queryIDList[2])) && p('module,title') && e('user,用户测试条件');           //查询ID为3的模块名及条件名
r($search->getByIDTest($queryIDList[3])) && p('module,title') && e('projectBuild,项目版本搜索');   //查询ID为4的模块名及条件名
r($search->getByIDTest($queryIDList[4])) && p('module,title') && e('executionBuild,执行版本搜索'); //查询ID为5的模块名及条件名
r($search->getByIDTest($queryIDList[5])) && p('module,title') && e('design,设计搜索');             //查询ID为6的模块名及条件名