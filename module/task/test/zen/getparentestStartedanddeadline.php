#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getParentEstStartedAndDeadline();
timeout=0
cid=0

- 执行taskTest模块的getParentEstStartedAndDeadlineTest方法，参数是array 第1条的estStarted属性 @2024-01-01
- 执行taskTest模块的getParentEstStartedAndDeadlineTest方法，参数是array 第999条的estStarted属性 @~~
- 执行taskTest模块的getParentEstStartedAndDeadlineTest方法，参数是array  @0
- 执行taskTest模块的getParentEstStartedAndDeadlineTest方法，参数是array 第1条的estStarted属性 @2024-01-01
- 执行taskTest模块的getParentEstStartedAndDeadlineTest方法，参数是array 第7条的estStarted属性 @2024-03-01

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

zendata('task')->gen(0);

global $tester;
$tester->dao->exec("
    INSERT INTO " . TABLE_TASK . "
    (id, parent, project, execution, name, path, estStarted, deadline, status, deleted) VALUES
    (1, 0, 1, 1, '父任务1', '1', '2024-01-01', '2024-01-31', 'wait', '0'),
    (2, 0, 1, 1, '父任务2', '2', '2024-01-15', '2024-02-15', 'wait', '0'),
    (3, 1, 1, 1, '子任务1-1', '1,3', '0000-00-00', '0000-00-00', 'wait', '0'),
    (7, 0, 1, 1, '父任务7', '7', '2024-03-01', '2024-03-31', 'wait', '0'),
    (999, 0, 1, 1, '测试任务', '999', '0000-00-00', '0000-00-00', 'wait', '0')
");

su('admin');

$taskTest = new taskZenTest();

r($taskTest->getParentEstStartedAndDeadlineTest(array(1))) && p('1:estStarted') && e('2024-01-01');
r($taskTest->getParentEstStartedAndDeadlineTest(array(999))) && p('999:estStarted') && e('~~');
r($taskTest->getParentEstStartedAndDeadlineTest(array())) && p() && e('0');
r($taskTest->getParentEstStartedAndDeadlineTest(array(1, 2))) && p('1:estStarted') && e('2024-01-01');
r($taskTest->getParentEstStartedAndDeadlineTest(array(7))) && p('7:estStarted') && e('2024-03-01');