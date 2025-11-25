#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getAfterCreateLocation();
timeout=0
cid=16427

- 执行executionTest模块的getAfterCreateLocationTest方法，参数是1, 1, '', 'doc', false, ''  @/doc-projectSpace-objectID=1.html
- 执行executionTest模块的getAfterCreateLocationTest方法，参数是1, 1, '', '', true, ''  @/execution-create-projectID=1&executionID=1&copyExecutionID=&planID=1&confirm=no.html
- 执行executionTest模块的getAfterCreateLocationTest方法，参数是1, 1, 'kanban', 'project', false, 'rnd'  @/project-index-projectID=1.html
- 执行executionTest模块的getAfterCreateLocationTest方法，参数是1, 1, 'kanban', 'project', false, 'lite'  @/project-execution-status=all&projectID=1.html
- 执行executionTest模块的getAfterCreateLocationTest方法，参数是1, 1, 'kanban', '', false, ''  @/execution-kanban-executionID=1.html
- 执行executionTest模块的getAfterCreateLocationTest方法，参数是0, 0, '', '', false, ''  @/execution-create-projectID=0&executionID=0.html
- 执行executionTest模块的getAfterCreateLocationTest方法，参数是5, 5, '', '', false, ''  @/execution-create-projectID=5&executionID=5.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

su('admin');

$executionTest = new executionZenTest();

r($executionTest->getAfterCreateLocationTest(1, 1, '', 'doc', false, '')) && p() && e('/doc-projectSpace-objectID=1.html');
r($executionTest->getAfterCreateLocationTest(1, 1, '', '', true, '')) && p() && e('/execution-create-projectID=1&executionID=1&copyExecutionID=&planID=1&confirm=no.html');
r($executionTest->getAfterCreateLocationTest(1, 1, 'kanban', 'project', false, 'rnd')) && p() && e('/project-index-projectID=1.html');
r($executionTest->getAfterCreateLocationTest(1, 1, 'kanban', 'project', false, 'lite')) && p() && e('/project-execution-status=all&projectID=1.html');
r($executionTest->getAfterCreateLocationTest(1, 1, 'kanban', '', false, '')) && p() && e('/execution-kanban-executionID=1.html');
r($executionTest->getAfterCreateLocationTest(0, 0, '', '', false, '')) && p() && e('/execution-create-projectID=0&executionID=0.html');
r($executionTest->getAfterCreateLocationTest(5, 5, '', '', false, '')) && p() && e('/execution-create-projectID=5&executionID=5.html');