#!/usr/bin/env php
<?php

/**

title=测试 projectZen::getKanbanData();
timeout=0
cid=0

- 执行getkanbandataTest模块的getKanbanDataTest方法，参数是'empty_data'  @0
- 执行getkanbandataTest模块的getKanbanDataTest方法，参数是'single_project' 第0条的key属性 @sprint
- 执行getkanbandataTest模块的getKanbanDataTest方法，参数是'multiple_projects'  @1
- 执行getkanbandataTest模块的getKanbanDataTest方法，参数是'multi_lane_projects'  @2
- 执行getkanbandataTest模块的getKanbanDataTest方法，参数是'with_executions'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/getkanbandata.unittest.class.php';

$getkanbandataTest = new getKanbanDataTest();

r($getkanbandataTest->getKanbanDataTest('empty_data')) && p() && e('0');
r($getkanbandataTest->getKanbanDataTest('single_project')) && p('0:key') && e('sprint');
r($getkanbandataTest->getKanbanDataTest('multiple_projects')) && p() && e('1');
r($getkanbandataTest->getKanbanDataTest('multi_lane_projects')) && p() && e('2');
r($getkanbandataTest->getKanbanDataTest('with_executions')) && p() && e('1');