#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('gettodosbyidlist')->gen(5);
}

/**

title=测试 todoModel->getTodosByIdList();
timeout=0
cid=19263

- 获取数据库中存在的待办信息第1条的name属性 @自定义的待办1
- 获取数据库中存在的待办信息第2条的name属性 @自定义的待办2
- 获取数据库中存在的待办信息第3条的name属性 @自定义的待办3
- 获取数据库中存在的待办信息第4条的name属性 @自定义的待办4
- 获取数据库中不存在的待办信息 @0

*/

initData();

global $tester;
$tester->loadModel('todo');

$todoIdList      = array(1, 2, 3, 4);
$todoIdListError = array(110);

r($tester->todo->gettodosbyidlist($todoIdList))      && p('1:name') && e('自定义的待办1'); // 获取数据库中存在的待办信息
r($tester->todo->gettodosbyidlist($todoIdList))      && p('2:name') && e('自定义的待办2'); // 获取数据库中存在的待办信息
r($tester->todo->gettodosbyidlist($todoIdList))      && p('3:name') && e('自定义的待办3'); // 获取数据库中存在的待办信息
r($tester->todo->gettodosbyidlist($todoIdList))      && p('4:name') && e('自定义的待办4'); // 获取数据库中存在的待办信息
r($tester->todo->gettodosbyidlist($todoIdListError)) && p()         && e('0');             // 获取数据库中不存在的待办信息
