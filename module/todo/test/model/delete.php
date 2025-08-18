#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('delete')->gen(10);
}

/**

title=测试删除todo todoModel->delete();
timeout=0
cid=1

- 删除指定id的待办，取消确认，查看是否有报错
 - 属性id @8
 - 属性deleted @0
- 删除后再次删除，查看是否还是已删除属性deleted @1
- 删除1未确认，查看是否删除属性deleted @0
- 删除2已确认，查看是否删除属性deleted @1
- 删除3已确认，查看是否删除属性deleted @1

*/

initData();

$todo = new todoTest();
r($todo->deleteTest(8, 'no')) && p('id,deleted') && e('8,0'); // 删除指定id的待办，取消确认，查看是否有报错

$todo->deleteTest(8, 'yes');
r($todo->getByIdTest(8)) && p('deleted') && e('1'); // 删除后再次删除，查看是否还是已删除

r($todo->deleteTest(1)) && p('deleted') && e('0'); // 删除1未确认，查看是否删除
r($todo->deleteTest(2, 'yes')) && p('deleted') && e('1'); // 删除2已确认，查看是否删除
r($todo->deleteTest(3, 'yes')) && p('deleted') && e('1'); // 删除3已确认，查看是否删除