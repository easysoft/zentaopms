#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-8');
$execution->name->range('瀑布项目1,等待阶段,进行阶段,关闭阶段,暂停阶段,顶级阶段,子阶段,叶子阶段');
$execution->type->range('project,stage{7}');
$execution->project->range('0,1{7}');
$execution->parent->range('0,1,1,1,1,1,6,6');
$execution->path->range("`,1,`,`,1,2,`,`,1,3,`,`,1,4,`,`,1,5,`,`,1,6,`,`,1,6,7,`,`,1,6,8,`");
$execution->status->range('doing,wait,doing,closed,suspended,wait,wait,wait');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('20220112 000000:0,20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(8);

/**

title=测试 executionModel::changeStatus2Doing();
timeout=0
cid=16278

- 测试等待状态执行更改为进行中状态 @1
- 测试已关闭状态执行更改为进行中状态 @1
- 测试已暂停状态执行更改为进行中状态 @1
- 测试顶级阶段更改为进行中状态 @1
- 测试子阶段更改为进行中状态 @1
- 测试无效执行ID的处理 @
- 验证状态更新结果检查status字段属性status @doing

*/

$executionTest = new executionModelTest();

r($executionTest->changeStatus2DoingObject(2)) && p('') && e('1');  // 测试等待状态执行更改为进行中状态
r($executionTest->changeStatus2DoingObject(4)) && p('') && e('1');  // 测试已关闭状态执行更改为进行中状态
r($executionTest->changeStatus2DoingObject(5)) && p('') && e('1');  // 测试已暂停状态执行更改为进行中状态
r($executionTest->changeStatus2DoingObject(6)) && p('') && e('1');  // 测试顶级阶段更改为进行中状态
r($executionTest->changeStatus2DoingObject(7)) && p('') && e('1');  // 测试子阶段更改为进行中状态
r($executionTest->changeStatus2DoingTest(0)) && p('') && e('');     // 测试无效执行ID的处理
r($executionTest->getExecutionStatusTest(2)) && p('status') && e('doing'); // 验证状态更新结果检查status字段