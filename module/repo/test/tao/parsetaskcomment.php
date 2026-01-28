#!/usr/bin/env php
<?php

/**

title=测试 repoTao::parseTaskComment();
timeout=0
cid=18121

- 测试步骤1：正常解析完成任务注释属性8 @8
- 测试步骤2：解析多个任务ID的注释
 - 属性1 @1
 - 属性8 @8
 - 属性12 @12
- 测试步骤3：解析开始任务注释属性5 @5
- 测试步骤4：解析记录工时注释属性3 @3
- 测试步骤5：解析空注释内容 @0
- 测试步骤6：解析无效格式注释 @0
- 测试步骤7：解析混合类型任务注释
 - 属性2 @2
 - 属性9 @9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$repo = new repoTaoTest();

// 测试数据：不同类型的任务注释
$finishTaskComment   = 'Finish Task #8 Cost:2h';                      // 完成单个任务
$multiTaskComment    = 'Finish Task #1,8,12 Cost:3h';                 // 完成多个任务
$startTaskComment    = 'Start Task #5 Cost:1h Left:7h';               // 开始任务
$effortTaskComment   = 'Effort Task #3 Cost:2h Left:6h';              // 记录工时
$emptyComment        = '';                                             // 空注释
$invalidComment      = 'This is just a normal comment without tasks'; // 无效格式
$mixedComment        = 'Start Task #2 Cost:1h Left:5h and Finish Task #9 Cost:3h'; // 混合类型

r($repo->parseTaskCommentTest($finishTaskComment))  && p('8')      && e('8');       // 测试步骤1：正常解析完成任务注释
r($repo->parseTaskCommentTest($multiTaskComment))   && p('1,8,12') && e('1,8,12');  // 测试步骤2：解析多个任务ID的注释
r($repo->parseTaskCommentTest($startTaskComment))   && p('5')      && e('5');       // 测试步骤3：解析开始任务注释
r($repo->parseTaskCommentTest($effortTaskComment))  && p('3')      && e('3');       // 测试步骤4：解析记录工时注释
r($repo->parseTaskCommentTest($emptyComment))       && p()         && e('0');       // 测试步骤5：解析空注释内容
r($repo->parseTaskCommentTest($invalidComment))     && p()         && e('0');       // 测试步骤6：解析无效格式注释
r($repo->parseTaskCommentTest($mixedComment))       && p('2,9')    && e('2,9');     // 测试步骤7：解析混合类型任务注释