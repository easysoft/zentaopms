#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=taskTao->getParentStatusById();
timeout=0
cid=18881

- 执行task模块的getParentStatusById方法，参数是1  @doing
- 执行task模块的getParentStatusById方法，参数是2  @doing
- 执行task模块的getParentStatusById方法，参数是3  @doing
- 执行task模块的getParentStatusById方法，参数是4  @doing
- 执行task模块的getParentStatusById方法，参数是5  @doing
- 执行task模块的getParentStatusById方法，参数是6  @doing
- 执行task模块的getParentStatusById方法，参数是7  @doing
- 执行task模块的getParentStatusById方法，参数是8  @doing
- 执行task模块的getParentStatusById方法，参数是9  @wait
- 执行task模块的getParentStatusById方法，参数是11  @doing
- 执行task模块的getParentStatusById方法，参数是12  @pause
- 执行task模块的getParentStatusById方法，参数是13  @pause
- 执行task模块的getParentStatusById方法，参数是14  @pause
- 执行task模块的getParentStatusById方法，参数是15  @doing
- 执行task模块的getParentStatusById方法，参数是16  @doing
- 执行task模块的getParentStatusById方法，参数是21  @wait
- 执行task模块的getParentStatusById方法，参数是22  @wait
- 执行task模块的getParentStatusById方法，参数是23  @wait
- 执行task模块的getParentStatusById方法，参数是31  @done
- 执行task模块的getParentStatusById方法，参数是32  @done
- 执行task模块的getParentStatusById方法，参数是33  @done
- 执行task模块的getParentStatusById方法，参数是41  @closed
- 执行task模块的getParentStatusById方法，参数是42  @cancel
- 执行task模块的getParentStatusById方法，参数是43  @cancel

*/

$task = $tester->loadModel('task');

/**
doing           + done   = doing
doing           + closed = doing
doing           + pause  = doing
doing           + cancel = doing
doing           + doing  = doing
doing           + wait   = doing
done            + wait   = doing
closeReasonDone + wait   = doing
*/

zenData('task')->loadYaml('taskdoing')->gen(26, true, false);

r($task->getParentStatusById(1)) && p() && e('doing');
r($task->getParentStatusById(2)) && p() && e('doing');
r($task->getParentStatusById(3)) && p() && e('doing');
r($task->getParentStatusById(4)) && p() && e('doing');
r($task->getParentStatusById(5)) && p() && e('doing');
r($task->getParentStatusById(6)) && p() && e('doing');
r($task->getParentStatusById(7)) && p() && e('doing');
r($task->getParentStatusById(8)) && p() && e('doing');
r($task->getParentStatusById(9)) && p() && e('wait');

/**
pause + done   = doing
pause + closed = doing
pause + pause  = doing
pause + cancel = doing
pause + doing  = doing
pause + wait   = doing
*/

zenData('task')->loadYaml('taskpause')->gen(18, true, false);

r($task->getParentStatusById(11)) && p() && e('doing');
r($task->getParentStatusById(12)) && p() && e('pause');
r($task->getParentStatusById(13)) && p() && e('pause');
r($task->getParentStatusById(14)) && p() && e('pause');
r($task->getParentStatusById(15)) && p() && e('doing');
r($task->getParentStatusById(16)) && p() && e('doing');

/**
wait + wait   = wait
wait + closed = wait
wait + cancel = wait
*/

zenData('task')->loadYaml('taskwait')->gen(9, true, false);

r($task->getParentStatusById(21)) && p() && e('wait');
r($task->getParentStatusById(22)) && p() && e('wait');
r($task->getParentStatusById(23)) && p() && e('wait');

/**
done + done   = done
done + closed = done
done + cancel = done
*/

zenData('task')->loadYaml('taskdone')->gen(9, true, false);

r($task->getParentStatusById(31)) && p() && e('done');
r($task->getParentStatusById(32)) && p() && e('done');
r($task->getParentStatusById(33)) && p() && e('done');

/**
closed + closed = closed
closed + cancel = closed
cancel + cancel = cancel
*/

zenData('task')->loadYaml('taskclose')->gen(9, true, false);

r($task->getParentStatusById(41)) && p() && e('closed');
r($task->getParentStatusById(42)) && p() && e('cancel');
r($task->getParentStatusById(43)) && p() && e('cancel');