#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

function initData()
{
    zenData('todo')->loadYaml('getvalidcyclelist')->gen(5);
}

/**

title=测试 todoModel->getValidCycleList();
timeout=0
cid=19264

- 获取有效的周期待办列表的第一条数据的ID和名称
 - 第1条的id属性 @1
 - 第1条的name属性 @`周期待办：提前1天，间隔一天生成`
- 获取有效的周期待办列表的第一条数据的ID和名称
 - 第2条的id属性 @2
 - 第2条的name属性 @`周期待办：每月的每天，提前1天生成`
- 获取有效的周期待办列表的第一条数据的ID和名称
 - 第3条的id属性 @3
 - 第3条的name属性 @`周期待办：每周的每天，提前1天生成`
- 获取有效的周期待办列表的第一条数据的ID和名称
 - 第4条的id属性 @4
 - 第4条的name属性 @`周期待办：提前1天，间隔一天生成`
- 获取有效的周期待办列表的第一条数据的ID和名称
 - 第5条的id属性 @5
 - 第5条的name属性 @`周期待办：每月的每天，提前1天生成`

*/

initData();

global $tester;
$tester->loadModel('todo');
r($tester->todo->getValidCycleList()) && p('1:id,name') && e('1,`周期待办：提前1天，间隔一天生成`');   // 获取有效的周期待办列表的第一条数据的ID和名称
r($tester->todo->getValidCycleList()) && p('2:id,name') && e('2,`周期待办：每月的每天，提前1天生成`'); // 获取有效的周期待办列表的第一条数据的ID和名称
r($tester->todo->getValidCycleList()) && p('3:id,name') && e('3,`周期待办：每周的每天，提前1天生成`'); // 获取有效的周期待办列表的第一条数据的ID和名称
r($tester->todo->getValidCycleList()) && p('4:id,name') && e('4,`周期待办：提前1天，间隔一天生成`');   // 获取有效的周期待办列表的第一条数据的ID和名称
r($tester->todo->getValidCycleList()) && p('5:id,name') && e('5,`周期待办：每月的每天，提前1天生成`'); // 获取有效的周期待办列表的第一条数据的ID和名称
