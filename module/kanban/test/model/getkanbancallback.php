#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getKanbanCallback();
timeout=0
cid=16922

- 执行kanbanTest模块的getKanbanCallbackTest方法，参数是1, 1 属性name @updateKanbanRegion
- 执行kanbanTest模块的getKanbanCallbackTest方法，参数是1, 1 第params条的0属性 @region1
- 执行kanbanTest模块的getKanbanCallbackTest方法，参数是999, 1 属性name @updateKanbanRegion
- 执行kanbanTest模块的getKanbanCallbackTest方法，参数是1, 999 属性name @updateKanbanRegion
- 执行kanbanTest模块的getKanbanCallbackTest方法，参数是0, 0 属性name @updateKanbanRegion

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

zendata('kanban')->loadYaml('kanban_getkanbancallback', false, 2)->gen(10);
zendata('kanbanregion')->loadYaml('kanbanregion_getkanbancallback', false, 2)->gen(10);

su('admin');

$kanbanTest = new kanbanTest();

r($kanbanTest->getKanbanCallbackTest(1, 1)) && p('name') && e('updateKanbanRegion');
r($kanbanTest->getKanbanCallbackTest(1, 1)) && p('params:0') && e('region1');
r($kanbanTest->getKanbanCallbackTest(999, 1)) && p('name') && e('updateKanbanRegion');
r($kanbanTest->getKanbanCallbackTest(1, 999)) && p('name') && e('updateKanbanRegion');
r($kanbanTest->getKanbanCallbackTest(0, 0)) && p('name') && e('updateKanbanRegion');