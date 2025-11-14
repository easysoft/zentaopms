#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

/**

title=测试批量完成待办 todoModel->batchFinish();
timeout=0
cid=19248

- 获取ID为1的待办状态属性status @wait
- 获取ID为2的待办状态属性status @doing
- 获取ID为3的待办状态属性status @done
- 获取ID为4的待办状态属性status @closed
- 获取批量完成后ID为1的待办状态属性status @done
- 获取批量完成后ID为2的待办状态属性status @done
- 获取批量完成后ID为3的待办状态属性status @done
- 获取批量完成后ID为4的待办状态属性status @done

*/

su('admin');

zenData('todo')->gen(10);

$todo = new todoTest();

r($todo->getByIdTest(1)) && p('status') && e('wait');   // 获取ID为1的待办状态
r($todo->getByIdTest(2)) && p('status') && e('doing');  // 获取ID为2的待办状态
r($todo->getByIdTest(3)) && p('status') && e('done');   // 获取ID为3的待办状态
r($todo->getByIdTest(4)) && p('status') && e('closed'); // 获取ID为4的待办状态

$todo->batchFinishTest(array(1, 2, 3, 4));

r($todo->getByIdTest(1)) && p('status') && e('done'); // 获取批量完成后ID为1的待办状态
r($todo->getByIdTest(2)) && p('status') && e('done'); // 获取批量完成后ID为2的待办状态
r($todo->getByIdTest(3)) && p('status') && e('done'); // 获取批量完成后ID为3的待办状态
r($todo->getByIdTest(4)) && p('status') && e('done'); // 获取批量完成后ID为4的待办状态
