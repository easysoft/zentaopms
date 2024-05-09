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

*/

initData();

$todo = new todoTest();
r($todo->deleteTest(8, 'no')) && p('id,deleted') && e('8,0');      // 删除指定id的待办，取消确认
$todo->deleteTest(8, 'yes');
r($todo->getByIdTest(8))      && p('deleted') && e('1'); // 验证删除id不存在的todo信息
