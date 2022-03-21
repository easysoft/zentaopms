#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->summaryTest();
cid=1
pid=1

敏捷执行任务统计 >> 本页共 <strong>14</strong> 个任务，未开始 <strong>4</strong>，进行中 <strong>3</strong>，总预计 <strong>3</strong> 工时，已消耗 <strong>15</strong> 工时，剩余 <strong>3</strong> 工时。
瀑布执行任务统计 >> 本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>17</strong> 工时，已消耗 <strong>15</strong> 工时，剩余 <strong>17</strong> 工时。
看板执行任务统计 >> 本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>20</strong> 工时，已消耗 <strong>15</strong> 工时，剩余 <strong>20</strong> 工时。

*/

$executionIDList = array('101', '131', '161');

$execution = new executionTest();
r($execution->summaryTest($executionIDList[0])) && p() && e('本页共 <strong>14</strong> 个任务，未开始 <strong>4</strong>，进行中 <strong>3</strong>，总预计 <strong>3</strong> 工时，已消耗 <strong>15</strong> 工时，剩余 <strong>3</strong> 工时。');  // 敏捷执行任务统计
r($execution->summaryTest($executionIDList[1])) && p() && e('本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>17</strong> 工时，已消耗 <strong>15</strong> 工时，剩余 <strong>17</strong> 工时。'); // 瀑布执行任务统计
r($execution->summaryTest($executionIDList[2])) && p() && e('本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>20</strong> 工时，已消耗 <strong>15</strong> 工时，剩余 <strong>20</strong> 工时。'); // 看板执行任务统计