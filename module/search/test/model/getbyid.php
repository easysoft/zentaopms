#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';
su('admin');

zenData('userquery')->gen(6);

/**

title=测试 searchModel->getByID();
timeout=0
cid=18299

- 查询ID为1的模块名及条件名
 - 属性module @task
 - 属性title @这是搜索条件名称1
- 查询ID为2的模块名及条件名
 - 属性module @task
 - 属性title @这是搜索条件名称2
- 查询ID为3的模块名及条件名
 - 属性module @task
 - 属性title @这是搜索条件名称3
- 查询ID为4的模块名及条件名
 - 属性module @task
 - 属性title @这是搜索条件名称4
- 查询ID为5的模块名及条件名
 - 属性module @task
 - 属性title @这是搜索条件名称5
- 查询ID为6的模块名及条件名
 - 属性module @task
 - 属性title @这是搜索条件名称6

*/

$search = new searchTest();

$queryIDList = array('1', '2', '3', '4', '5', '6');

r($search->getByIDTest($queryIDList[0])) && p('module,title') && e('task,这是搜索条件名称1');  //查询ID为1的模块名及条件名
r($search->getByIDTest($queryIDList[1])) && p('module,title') && e('task,这是搜索条件名称2');  //查询ID为2的模块名及条件名
r($search->getByIDTest($queryIDList[2])) && p('module,title') && e('task,这是搜索条件名称3');  //查询ID为3的模块名及条件名
r($search->getByIDTest($queryIDList[3])) && p('module,title') && e('task,这是搜索条件名称4');  //查询ID为4的模块名及条件名
r($search->getByIDTest($queryIDList[4])) && p('module,title') && e('task,这是搜索条件名称5');  //查询ID为5的模块名及条件名
r($search->getByIDTest($queryIDList[5])) && p('module,title') && e('task,这是搜索条件名称6');  //查询ID为6的模块名及条件名